<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PaymentProfile;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class TransactionController extends AccountController
{
    public function createPaymentProfile(Request $request)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(config('services.authorize_net.api_login_id'));
        $merchantAuthentication->setTransactionKey(config('services.authorize_net.transaction_key'));

        if (!$this->company->id_customer_profile) {
            $requestAnetAPI = new AnetAPI\CreateCustomerProfileRequest();
            $requestAnetAPI->setMerchantAuthentication($merchantAuthentication);

            $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
            $paymentProfile->setCustomerType('individual'); // or 'business' if applicable

            // Set the billing information for the customer
            $billTo = new AnetAPI\CustomerAddressType();
            $billTo->setAddress($this->company->address);
            $billTo->setCity($this->company->city);
            $billTo->setState($this->company->region);
            $billTo->setCountry($this->company->country);
            $billTo->setPhoneNumber($this->company->telephone);

            $paymentProfile->setBillTo($billTo);

            // Set the payment details (credit card information)
            $payment = new AnetAPI\PaymentType();
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($request->cc_number); // Replace with the actual card number
            $creditCard->setExpirationDate($request->expiry_year . '-' . $request->expiry_month); // MMYY format
            $creditCard->setCardCode($request->cvv); // CVV code

            $payment->setCreditCard($creditCard);
            $paymentProfile->setPayment($payment);

            // Add the payment profile to the customer's profile
            $paymentProfiles = array($paymentProfile);

            $customerProfile = new AnetAPI\CustomerProfileType();
            $customerProfile->setMerchantCustomerId($this->company->id); // Your unique customer ID
            //$customerProfile->setEmail('customer@example.com');
            $customerProfile->setDescription($this->company->name);
            $customerProfile->setPaymentProfiles($paymentProfiles);
            $requestAnetAPI->setProfile($customerProfile);

            // Execute the request
            $controller = new AnetController\CreateCustomerProfileController($requestAnetAPI);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

            if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
                $customerProfileId = $response->getCustomerProfileId();
                Company::where('id', $this->company->id)->update(['id_customer_profile' => $customerProfileId]);
                $paymentProfileId = $response->getCustomerPaymentProfileIdList()[0];
                $payment_profile = PaymentProfile::createPaymentProfile($request, $this->company->id, $paymentProfileId);

                if ($payment_profile) {
                    return response()->json([
                        'success' => true,
                        'message' => __('Customer profile created successfully'),
                    ], Response::HTTP_OK);
                }
                return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
                // Store $paymentProfileId in your database for future use
            } else {
                $errorMessages = $response->getMessages()->getMessage();
                // Handle errors
                // $error = $response->getMessage();
                return self::httpBadRequest($errorMessages);
            }
        } else {
            $customerProfile = new AnetAPI\CustomerProfileType();
            $customerProfile->setMerchantCustomerId($this->company->id_customer_profile);

            // Create a payment profile
            $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
            $paymentProfile->setCustomerType('individual'); // or 'business' if applicable

            // Set the payment details (credit card information)
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($request->cc_number); // Use the card number
            $creditCard->setExpirationDate($request->expiry_year . '-' . $request->expiry_month); // Use the MMYY format
            $creditCard->setCardCode($request->cvv); // Use the CVV code

            $payment = new AnetAPI\PaymentType();
            $payment->setCreditCard($creditCard);
            $paymentProfile->setPayment($payment);

            // Create a request to add the payment profile
            $addPaymentProfileRequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
            $addPaymentProfileRequest->setMerchantAuthentication($merchantAuthentication);
            $addPaymentProfileRequest->setCustomerProfileId($this->company->id_customer_profile);
            $addPaymentProfileRequest->setPaymentProfile($paymentProfile);

            // Execute the request to create the payment profile
            $addPaymentProfileController = new AnetController\CreateCustomerPaymentProfileController($addPaymentProfileRequest);
            $addPaymentProfileResponse = $addPaymentProfileController->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);

            if (($addPaymentProfileResponse != null) && ($addPaymentProfileResponse->getMessages()->getResultCode() == "Ok")) {

                // The payment profile was successfully created
                $paymentProfileId = $addPaymentProfileResponse->getCustomerPaymentProfileId();
                $payment_profile = PaymentProfile::createPaymentProfile($request, $this->company->id, $paymentProfileId);

                if ($payment_profile) {
                    return response()->json([
                        'success' => true,
                        'message' => __('Payment profile added successfully'),
                    ], Response::HTTP_OK);
                }
            } else {
                // Handle the case where payment profile creation failed
                $errorMessages = $addPaymentProfileResponse->getMessages()->getMessage()[0]->getText();
                return self::httpBadRequest($errorMessages);
            }
        }
    }

    public function getCards()
    {
        $paymentProfiles = $this->paymentProfileRepository->getPaymentProfiles($this->company->id);
        return response()->json([
            'success' => true,
            'payment_profiles' => $paymentProfiles,
        ], Response::HTTP_OK);
    }

}

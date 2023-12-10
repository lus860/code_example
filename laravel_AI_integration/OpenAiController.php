<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenAI\Laravel\Facades\OpenAI;

class OpenAiController extends AccountController
{
    public function formTemplates(Request $request)
    {
        if ($request) {
            $prompt = $request->input('prompt');
            $prompt .= ' Can you provide the answer in json format, similar to this
```
    {
        "name": "form name",
        "fields": [
            {
                "field_type": "Multi-choice group",// preferable types are "Rating", "Grading", "eNPS", "Range", "Single choice group", "Multi-choice group", "Long text", "Short text"
                "label": "field label",
                "description": "description",// it can be null
                "mandatory": true, // true or false
                "explanation": true, // true or false
                "type_content": [{name: "choice 1", default: false}, {name: "choice 2", default: false}, {name: "choice 3", default: false}],
                "options": [{name: "choice 1", default: false}, {name: "choice 2", default: false}, {name: "choice 3", default: false}],
            }
         ]
    }
```
?';
            $prompt .= ' Here are different cases of field_type, and type_content is based on it

    ```{
        "fields": [
            {
                "field_type": "Rating",
                "type_content": {
                   "1": "Strongly disagree",
                   "2": "Disagree",
                   "3": "Neither agree or disagree",
                   "4": "Agree",
                   "5": "Strongly agree"
               }
            },
            {
                "field_type": "Grading",
                "type_content": {
                   "grade_type": "stars", // stars or dots
                   "system_points": 5, // min 5, max 10
                }
            },
            {
                "field_type": "eNPS",
                "type_content": {}
            },
            {
                "field_type": "Range",
                "type_content": {added_range_numbers: [0, 10]} // min number 0, and max number 100
            },
            {
                "field_type": "Single choice group",
                "type_content": [
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    }
                ],
                "options": [
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    }
                ]
            },
            {
                "field_type": "Multi-choice group",
                "type_content": [
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    }
                ],
                "options": [
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    },
                    {
                        "name": "name",
                        "default": false, // true or false
                    }
                ]
            },
            {
                "field_type": "Long text",
                "label": "field label",
                "description": "description",
                "mandatory": false, // true or false
                "type_content": {}
            },
            {
                "field_type": "Short text",
                "label": "field label",
                "description": "description",
                "mandatory": false, // true or false
                "type_content": {}
            }
        ]
    }```';

            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            return response()->json([
                'success' => true,
                'content' => json_decode($result->choices[0]->message->content),
            ], Response::HTTP_OK);
        }

        return self::httpBadRequest(self::SOMETHING_WENT_WRONG);
    }

}

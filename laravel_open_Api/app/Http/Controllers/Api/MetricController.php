<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\MetricEditRequest;
use App\Http\Requests\Api\RemoveMetricRequest;
use App\Models\Metric;
use App\Notifications\Api\MetricCRUDNotifications;
use Illuminate\Http\Response;
use App\Http\Requests\Api\MetricAddRequest;

class MetricController extends BaseController
{
    public function index()
    {
        $sortByType = 'desc';

        if (!empty($request->order) && $request->order === 'asc') {
            $sortByType = $request->order;
        }

        $metrics = $this->metricRepository->getMetrics($this->company->id, $sortByType);

        if (!$metrics) {
            return self::httpBadRequest();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'OK',
            'metrics' => $metrics,
        ], Response::HTTP_OK);

    }

    public function store(MetricAddRequest $request)
    {
        $metric = Metric::createMetricApi($request, $this->company->id);
        if ($metric) {
            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'OK',
            ], Response::HTTP_CREATED);
        }

        return self::httpBadRequest('Cannot add metrics. Account limits reached', 4040);
    }

    public function update(MetricEditRequest $request)
    {
        $metric = $this->metricRepository->getMetricById($request->id, $this->company->id);

        $oldName = $metric->name;

        if (!$metric) {
            return self::httpBadRequest();
        }

        $res = Metric::updateMetric($metric, $request);

        if (isset($res) && $res) {
            if (($oldName !== $metric->name) && $metric->owner) {
                if ($request->silent) {
                    $metric->owner->notify(new MetricCRUDNotifications($metric, 'edit'));
                }
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'OK',
            ], Response::HTTP_OK);
        } else {
            return self::httpBadRequest('Cannot update metrics. Account limits reached', 4040);
        }
    }

    public function destroy(RemoveMetricRequest $request)
    {
        $metric = $this->metricRepository->getMetricById($request->id, $this->company->id);

        if (!$metric) {
            return self::httpBadRequest();
        }

        $res = $metric->delete();
        if (isset($res) && $res) {
            if ($request->silent && $metric->owner) {
                $metric->owner->notify(new MetricCRUDNotifications($metric, 'delete'));
            }

            return response()->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'OK',
            ], Response::HTTP_OK);
        } else {
            return self::httpBadRequest('Cannot delete metrics. Account limits reached', 4040);
        }
    }
}

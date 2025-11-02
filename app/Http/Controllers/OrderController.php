<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    const DEFAULT_MESSAGE_ERROR = 'Order error';
    const DEFAULT_MESSAGE_SUCCESS = 'Order success';

    const CONF_MSG_KEY_SUCCESS = 'order';
    const CONF_MSG_KEY_ERROR = 'order';

    protected $model = Order::class;

    public function create(OrderRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $request->user();

        $createdOrder = Order::createByTransaction($user, $data);
        
        if (!$createdOrder) {
            $this->error($this->getErrorMessage('not_create'));
        }

        return $this->success(['data' => $createdOrder]);
    }

    public function getLastOrder(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return $this->error();
        }

        $lastOrder = $user->getLastOrder();

        if (!$lastOrder) {
            return $this->error($this->getErrorMessage('last_not_found'), 404);
        }

        return $this->success(['data' => $lastOrder]);
    }
}

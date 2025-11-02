<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    const DEFAULT_MESSAGE_ERROR = 'Error';
    const DEFAULT_MESSAGE_SUCCESS = 'Success';

    /**
     * @param class-string
     */
    protected $model;

    /**
     * @param string[]
     */
    protected $errorMessages = [];

    /**
     * @param string[]
     */
    protected $successMessages = [];

    public function getById(int $id): JsonResponse
    {
        if ($this->model) {
            $el = $this->model::getById($id);

            if (!$el) {
                return $this->notFound();
            }

            return $this->success(['data' => $el->toArray()]);
        }

        return $this->error();
    }

    public function getErrorMessage(string $key): string
    {
        if (!empty($this->errorMessages[$key])) {
            return strval($this->errorMessages[$key]);
        }

        return static::DEFAULT_MESSAGE_ERROR;
    }

    public function getSuccessMessage(string $key): string
    {
        if (!empty($this->successMessages[$key])) {
            return strval($this->successMessages[$key]);
        }

        return static::DEFAULT_MESSAGE_SUCCESS;
    }

    protected function error(string|array $data = [], int $status = 400): JsonResponse
    {
        if (!is_array($data)) {
            $data = [
                'message' => $data,
            ];
        }

        $data['status'] = 'error';

        return response()->json($data, $status);
    }

    protected function notFound(string $message = 'Запрошенный ресурс не найден'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function success(string|array $data = [], int $status = 200): JsonResponse
    {
        if (!is_array($data)) {
            $data = [
                'message' => $data,
            ];
        }

        $data['status'] = 'success';

        if (!isset($data['data'])) {
            $data['data'] = [];
        }

        return response()->json($data, $status);
    }
}

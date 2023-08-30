<?php
namespace App\Traits;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
trait RestfulTrait{

    public function apiResponse($data = null  , $code = 200 , $message = null , $paginate = null){
        $arrayResponse = [
            'data' => $data ,
            'status' => $code == 200 || $code==201 || $code==204 || $code==205 ,
            'message' => $message ,
            'code' => $code ,
            'paginate' => $paginate
        ];
        return response($arrayResponse,$code);
    }
    // public function apiValidation($request , $array){
    //     $validator = Validator::make($request->all(), $array);
    //     if ($validator->fails()) {
    //         return $this->apiResponse(null, ApiController::STATUS_VALIDATION, $validator->messages());
    //     }
    //     return $validator->validated();
    // }

    public function apiValidation($request, $array)
    {
        $validator = Validator::make($request->all(), $array);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode(', ', $errors);
            return $this->apiResponse(null, ApiController::STATUS_VALIDATION, $errorMessage);
        }
        return $validator->validated();
    }


    public function formatPaginateData($data)
    {
        $paginated_arr = $data->toArray();
        return $paginateData = [
            'currentPage'   => $paginated_arr['current_page'],
            'from'          => $paginated_arr['from'],
            'to'            => $paginated_arr['to'],
            'total'         => $paginated_arr['total'],
            'per_page'      => $paginated_arr['per_page'],
        ];
    }
}

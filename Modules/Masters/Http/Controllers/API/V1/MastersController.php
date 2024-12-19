<?php

namespace Modules\Masters\Http\Controllers\Api\V1;

use App\Http\Controllers\ApiBaseController;
use Auth;
use Illuminate\Http\Request;
use Modules\Masters\Entities\Category;
use Modules\Masters\Entities\Destination;
use Modules\Masters\Entities\Country;
use Modules\Masters\Entities\Currency;
use Modules\User\Entities\User;
use Modules\Masters\Entities\Banner;

class MastersController extends ApiBaseController
{

    public function __construct()
    {
        $this->success      = '200';
        $this->ok           = '200';
        $this->accessDenied = '400';
    }

    public function banners(Request $request)
    {
        try {
            $banners = Banner::select('*')->where('status','active')->orderBy('name', 'ASC')->get();

            if(!empty($banners->toArray())){
                foreach ($banners as $key => $banner) {
                    if($banner->file !=''){
                        $banner->image_url = \URL::to('/').'/uploads/banners/'.$banner->file;
                    }
                }
            }

            return $this->sendSuccessResponse($banners, $this->success);
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }

    public function countries(Request $request)
    {
        try {
            $countries = Country::select('code','name')->orderBy('name', 'ASC')->get();
            return $this->sendSuccessResponse($countries, $this->success);
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }

    public function currencies(Request $request)
    {
        try {
            $currencies = Currency::select('*')->orderBy('name', 'ASC')->get();
            return $this->sendSuccessResponse($currencies, $this->success);
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/masters/categories",
     *     tags={"Masters"},
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API to get categories",
     *     operationId="categories",
     *     @OA\Parameter(name="pagniation",in="query",required=false,
     *     description="pagniation",
     *         @OA\Schema(
     *             type="string",
     *             format="no"
     *         )
     *     ),
     *     @OA\Parameter(name="is_featured",in="query",required=false,
     *     description="is_featured",
     *         @OA\Schema(
     *             type="string",
     *             example="1",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */

    public function categories(Request $request)
    {
        \DB::beginTransaction();
        try {

            $user = \Auth::user();

            $perpage = \Config::get('constants.PAGE.PER_PAGE');
            if (isset($request->pagniation) && $request->pagniation == 'no') {
                $masters = Category::select('m_categories.name as name', 'm_categories.id as id', 'm_categories.status as status', 'file as image','thumbnail')
                    ->where(function ($query) use ($request) {
                        if (isset($request->is_featured)) {
                            $query->where('m_categories.is_featured', $request->is_featured);
                        }
                    })
                    ->where('m_categories.status', 'active')
                    ->whereNull('m_categories.deleted_at')
                    ->groupBy('m_categories.id')
                    ->orderBy('m_categories.name', 'ASC')
                    ->get();
            } else {
                $masters = Category::select('m_categories.name as name', 'm_categories.id as id', 'm_categories.status as status', 'file as image','thumbnail')
                    ->where(function ($query) use ($request) {
                        if (isset($request->is_featured)) {
                            $query->where('m_categories.is_featured', $request->is_featured);
                        }
                    })
                    ->where('m_categories.status', 'active')
                    ->whereNull('m_categories.deleted_at')
                    ->groupBy('m_categories.id')
                    ->orderBy('m_categories.name', 'ASC')
                    ->paginate($perpage);
            }

            foreach ($masters as $key => $category) {
                if ($category->image != '') {
                    $category->image     = \URL::to('/') . '/uploads/categories/' . $category->image;
                    $category->thumbnail = \URL::to('/') . '/uploads/categories/' . $category->thumbnail;
                } else {
                    $category->image     = \URL::to('/') . '/no1.jpg';
                    $category->thumbnail = \URL::to('/') . '/no1.jpg';
                }
            }

            \DB::commit();

            if (!empty($masters->toArray())) {
                $categories = $masters->toArray();
                $categories = $categories['data'];
                $pagination = \Helpers::pagination($masters);

                $data['data'] = $categories;
                $data['meta'] = $pagination;
            }

            return $this->sendSuccessResponse($data, $this->success);

            return $this->sendSuccessResponse($categories, $this->success);

        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/masters/categories/{category_id}",
     *     tags={"Masters"},
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API to get categories details",
     *     operationId="categoryDetails",
     *     @OA\Parameter(name="category_id",in="path",required=true,
     *     description="category_id",
     *         @OA\Schema(
     *             type="string",
     *             format="no"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */

    public function categoryDetails(Request $request, $category_id)
    {
        \DB::beginTransaction();
        try {

           $user = \Auth::user();

            $category = Category::select('m_categories.name as name', 'm_categories.id as id', 'm_categories.status as status', 'file', 'description', 'is_featured')
                ->where('m_categories.id', $category_id)
                ->first();
            if($category){
                if ($category->file != '') {
                    $category->image     = \URL::to('/') . '/uploads/categories/full_' . $category->file;
                    $category->thumbnail = \URL::to('/') . '/uploads/categories/thumbnail_' . $category->file;
                } else {
                    $category->image     = \URL::to('/') . '/no1.jpg';
                    $category->thumbnail = \URL::to('/') . '/no1.jpg';
                }

                return $this->sendSuccessResponse($category, $this->success);
            }else{
                return $this->sendFailureResponse("Category not found");
            }

        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/masters/destinations",
     *     tags={"Masters"},
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API to get destinations",
     *     operationId="destinations",
     *     @OA\Parameter(name="pagniation",in="query",required=false,
     *     description="pagniation",
     *         @OA\Schema(
     *             type="string",
     *             format="no"
     *         )
     *     ),
     *     @OA\Parameter(name="is_featured",in="query",required=false,
     *     description="is_featured",
     *         @OA\Schema(
     *             type="string",
     *             example="1",
     *             format="int32"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */

    public function destinations(Request $request)
    {
        \DB::beginTransaction();
        try {

            $user = \Auth::user();

            $perpage = \Config::get('constants.PAGE.PER_PAGE');
            if (isset($request->pagniation) && $request->pagniation == 'no') {
                $masters = Destination::select('m_destinations.name as name', 'm_destinations.id as id', 'm_destinations.status as status', 'file','smith_destination_id','thumbnail')
                    ->where(function ($query) use ($request) {
                        if (isset($request->is_featured)) {
                            $query->where('m_destinations.is_featured', $request->is_featured);
                        }
                    })
                    ->where('m_destinations.status', 'active')
                    ->whereNull('m_destinations.deleted_at')
                    ->groupBy('m_destinations.id')
                    ->orderBy('m_destinations.name', 'ASC')
                    ->get();
            } else {
                $masters = Destination::select('m_destinations.name as name', 'm_destinations.id as id', 'm_destinations.status as status', 'file','smith_destination_id','thumbnail')
                    ->where(function ($query) use ($request) {
                        if (isset($request->is_featured)) {
                            $query->where('m_destinations.is_featured', $request->is_featured);
                        }
                    })
                    ->where('m_destinations.status', 'active')
                    ->whereNull('m_destinations.deleted_at')
                    ->groupBy('m_destinations.id')
                    ->orderBy('m_destinations.name', 'ASC')
                    ->paginate($perpage);
            }

            foreach ($masters as $key => $destination) {
                if ($destination->file != '') {
                    $destination->image     = \URL::to('/') . '/uploads/destinations/' . $destination->file;
                    $destination->thumbnail = \URL::to('/') . '/uploads/destinations/' . $destination->thumbnail;
                } else {
                    $destination->image     = \URL::to('/') . '/no1.jpg';
                    $destination->thumbnail = \URL::to('/') . '/no1.jpg';
                }
            }

            \DB::commit();

            if (!empty($masters->toArray())) {
                $destinations = $masters->toArray();
                $destinations = $destinations['data'];
                $pagination = \Helpers::pagination($masters);

                $data['data'] = $destinations;
                $data['meta'] = $pagination;
            }

            return $this->sendSuccessResponse($data, $this->success);

            return $this->sendSuccessResponse($destinations, $this->success);

        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/masters/destinations/{destination_id}",
     *     tags={"Masters"},
     *     security={
     *      {"bearerAuth": {}},
     *     },
     *     description="API to get destinations details",
     *     operationId="destinationDetails",
     *     @OA\Parameter(name="destination_id",in="path",required=true,
     *     description="destination_id",
     *         @OA\Schema(
     *             type="string",
     *             format="no"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="unexpected error",
     *         @OA\Schema(ref="#/components/schemas/Error")
     *     )
     * )
     */

    public function destinationDetails(Request $request, $destination_id)
    {
        \DB::beginTransaction();
        try {

           $user = \Auth::user();

            $destination = Destination::select('m_destinations.name as name', 'm_destinations.id as id', 'm_destinations.status as status', 'file', 'thumbnail','description', 'is_featured','smith_destination_id')
                ->where('m_destinations.id', $destination_id)
                ->first();
            if($destination){
                if ($destination->file != '') {
                    $destination->image     = \URL::to('/') . '/uploads/destinations/' . $destination->file;
                    $destination->thumbnail = \URL::to('/') . '/uploads/destinations/' . $destination->thumbnail;
                } else {
                    $destination->image     = \URL::to('/') . '/no1.jpg';
                    $destination->thumbnail = \URL::to('/') . '/no1.jpg';
                }

                return $this->sendSuccessResponse($destination, $this->success);
            }else{
                return $this->sendFailureResponse("destination not found");
            }

        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendFailureResponse($e->getMessage());
        }
    }
}

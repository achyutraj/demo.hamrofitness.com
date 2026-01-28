<?php

namespace App\Http\Controllers\API\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\GymMembership;
use App\Models\GymPurchase;
use App\Models\GymSetting;
use App\Models\GymTutorial;
use App\Models\Merchant;
use App\Models\MobileApp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Hamrofitness Api Documentation",
 *      description="Hamrofitness Api Documentation",
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 * 
 *  */
class FrontendController extends Controller
{
    protected $data = [];

    /**
     * @OA\Get(
     *     path="/api/merchant/home",
     *     summary="Get home details",
     *     tags={"Frontend"},
     *     security={{"bearer": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function home()
    {
        $this->data['mobileAppInfo'] = MobileApp::select('about','services','price_plan','notice_image','logo','offer_image','banner_image1',
            'banner_image2','banner_image3','address','fb_url','google_url','twitter_url','youtube_url','contact_mail')
            ->where('detail_id',1)->first();
        if ($this->data['mobileAppInfo'] !== null) {
            $file_path = asset('uploads/mobile_app/') . '/';
            $this->data['mobileAppInfo']->logo =  !is_null( $this->data['mobileAppInfo']->logo) ? $file_path . $this->data['mobileAppInfo']->logo : null;
            $this->data['mobileAppInfo']->offer_image =  !is_null( $this->data['mobileAppInfo']->offer_image) ? $file_path . $this->data['mobileAppInfo']->offer_image : null;
            $this->data['mobileAppInfo']->banner_image1 =  !is_null( $this->data['mobileAppInfo']->banner_image1) ? $file_path . $this->data['mobileAppInfo']->banner_image1 : null;
            $this->data['mobileAppInfo']->banner_image2 =  !is_null( $this->data['mobileAppInfo']->banner_image2) ? $file_path . $this->data['mobileAppInfo']->banner_image2 : null;
            $this->data['mobileAppInfo']->banner_image3 =  !is_null( $this->data['mobileAppInfo']->banner_image3) ? $file_path . $this->data['mobileAppInfo']->banner_image3 : null;
        }
        return response()->json([
            'data' => $this->data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/merchant-info/{mobileApp}",
     *     summary="Get merchant information",
     *     tags={"Frontend"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="mobileApp",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function merchantInfo(MobileApp $mobileApp): JsonResponse
    {
        $this->data['mobileAppInfo'] = $mobileApp;
        if ($this->data['mobileAppInfo'] !== null) {
            $file_path = asset('uploads/mobile_app/') . '/';
            $this->data['mobileAppInfo']->logo =  !is_null( $this->data['mobileAppInfo']->logo) ? $file_path . $this->data['mobileAppInfo']->logo : null;
            $this->data['mobileAppInfo']->offer_image =  !is_null( $this->data['mobileAppInfo']->offer_image) ? $file_path . $this->data['mobileAppInfo']->offer_image : null;
            $this->data['mobileAppInfo']->banner_image1 =  !is_null( $this->data['mobileAppInfo']->banner_image1) ? $file_path . $this->data['mobileAppInfo']->banner_image1 : null;
            $this->data['mobileAppInfo']->banner_image2 =  !is_null( $this->data['mobileAppInfo']->banner_image2) ? $file_path . $this->data['mobileAppInfo']->banner_image2 : null;
            $this->data['mobileAppInfo']->banner_image3 =  !is_null( $this->data['mobileAppInfo']->banner_image3) ? $file_path . $this->data['mobileAppInfo']->banner_image3 : null;

        }
        return response()->json([
            'data' => $this->data,
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/tutorials",
     *     summary="Get tutorial information. Add type: image,text,image,audio,video",
     *     tags={"Frontend"},
     *     security={{"bearer": {}}},
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="The type of tutorial (optional)",
     *         required=false, 
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */

    public function tutorialLists(): JsonResponse
    {
        $type = request()->get('type');
        // Fetch tutorials based on the 'type' parameter if provided
        $tutorials = GymTutorial::where('status', 1)
                    ->when($type, function ($query, $type) {
                        return $query->where('type', $type);
                    })->get();

        $file_path = asset('uploads/gym_tutorials/') . '/';
        $this->data['tutorials'] = $tutorials->map(function ($tutorial) use ($file_path) {
        return [
            'title' => $tutorial->title,
            'description' => $tutorial->description,
            'type' => $tutorial->type,
            'iframe_code' => $tutorial->iframe_code,
            'image' => $tutorial->image ? $file_path . $tutorial->image : null,
        ];
        })->toArray();

        $this->data['total'] = $tutorials->count();

        return response()->json([
            'data' => $this->data,
        ]);
    }

      /**
     * @OA\Get(
     *     path="/api/payment-sources",
     *     summary="Get Payment Sources information",
     *     tags={"Frontend"},
     *     security={{"bearer": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function paymentSources(): JsonResponse
    {
        $sources = listPaymentType();
        return response()->json([
            'data' => $sources,
        ]);
    }

    public function getGymInactiveClients($gym_slug)
    {
        $this->data['common_details'] = Common::select('id', 'title')->where('slug', $gym_slug)->first();
        $this->data['inactive_clients'] = [];
        $inactives = GymPurchase::with('client')
            ->where('detail_id', $this->data['common_details']->id)
            ->whereDate('expires_on', today())->get();
        foreach ($inactives as $inactive) {
            $this->data['inactive_clients'][] = [
                'client_id' => $inactive->client->uuid,
                'expires_on' => $inactive->expires_on->format('Y-m-d'),
            ];
        }

        $this->data['total'] = $inactives->count();
        return response()->json([
            'data' => $this->data,
        ]);
    }
}

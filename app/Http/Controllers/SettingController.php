<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSetting;
use App\Settings\MailServerSetting;
use App\Settings\SocialMediaSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function settings(
        GeneralSetting $generalSettings,
        MailServerSetting $mailServerSettings,
        SocialMediaSetting $socialMediaSettings
    ) {
        try {
            return response()->json([
                "general" => [
                    "company_name" => $generalSettings->company_name,
                    "system_url" => $generalSettings->system_url,
                ],
                "mail_server" => [
                    "host" => $mailServerSettings->host,
                    "email" => $mailServerSettings->email,
                    "password" => $mailServerSettings->password,
                ],
                "social_media" => [
                    "facebook" => $socialMediaSettings->facebook,
                    "instagram" => $socialMediaSettings->instagram,
                    "twitter" => $socialMediaSettings->twitter,
                    "youtube" => $socialMediaSettings->youtube,
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function saveGeneralSettings(Request $rq, GeneralSetting $setting)
    {
        try {

            $setting->company_name = $rq->company_name ?? "";
            $setting->system_url = $rq->system_url ?? "";
            $setting->save();

            return response()->json([
                "status" => 201,
                "message" => "General settings updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function saveMailServerSettings(Request $rq, MailServerSetting $setting)
    {
        try {

            $setting->host = $rq->host ?? "";
            $setting->email = $rq->email ?? "";
            $setting->password = $rq->password ?? "";
            $setting->save();

            return response()->json([
                "status" => 201,
                "message" => "Mail Server settings updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }


    public function saveSocialSettings(Request $rq, SocialMediaSetting $setting)
    {
        try {

            $setting->facebook = $rq->facebook ?? "";
            $setting->instagram = $rq->instagram ?? "";
            $setting->twitter = $rq->twitter ?? "";
            $setting->youtube = $rq->youtube ?? "";
            $setting->save();

            return response()->json([
                "status" => 201,
                "message" => "Social settings updated successfully"
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => 404,
                "message" => $th?->getMessage()
            ]);
        }
    }
}

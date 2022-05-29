<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Api\V1\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CreateGroupController extends Controller
{
    public function index(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'urls' => ['required', 'json'],
        ]);

        //validate JSON
        if ($validate->fails()) {
            return response(['data' => ['message' => $validate->errors()]]);
        }

        $urls = json_decode($request->urls, true);

        $validate = Validator::make($urls, [
            '*' => ['url', 'unique:links,url'],
        ]);

        //validate URL
        if ($validate->fails()) {
            return response(['data' => ['message' => $validate->errors()]]);
        }


        $urlIn = explode('checkin', route('link_slog', 'checkin'))[0];
        $return_url = [];

        $ip = $request->ip();
        $user_agent = $request->header('user-agent');

        foreach ($urls as $url) {
            do {
                $random = Str::random(10);
            } while (Link::where('slog', $random)->first('slog'));


            $data = new Link();
            $data->url = $url;
            $data->slog = $random;
            $data->user_id = $request->user_id;
            $data->ip = $ip;
            $data->api = 1;
            $data->user_agent = $user_agent;
            $data->save();


            array_push($return_url, $urlIn . $random);
        }

        return response(['data' => ['urls' => $return_url]], 201);
    }
}

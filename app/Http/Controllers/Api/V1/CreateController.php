<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Api\V1\Link;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CreateController extends Controller
{
    public function index(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'url' => ['required', 'url', 'unique:links,url'],
        ]);

        //validate
        if ($validate->fails()) {
            return response(['data' => ['message' => $validate->errors()]]);
        }

        do {
            $random = Str::random(10);
            var_dump($random);
        } while (Link::where('slog', $random)->first('slog'));


        $data = new Link;
        $data->url = $request->url;
        $data->slog = $random;
        $data->user_id = $request->user_id;
        $data->api = 1;
        $data->save();

        $url = explode('checkin', route('link_slog', 'checkin'))[0];

        return response(['data' => ['url' => $url . $random]], 201);
    }
}

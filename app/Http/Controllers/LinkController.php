<?php

namespace App\Http\Controllers;

use App\Models\Api\V1\Link;
use App\Models\Api\V1\LinkViews;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index($slog, Request $request)
    {

        $data = Link::where('slog', $slog)->first();
        if ($data) {

            $data->view = $data->view + 1;
            $data->save();

            $ip = $request->ip();
            $user_agent = $request->header('user-agent');
            $referer = $request->header('referer');

            $dataView = new LinkViews;
            $dataView->link_id = $data->id;
            $dataView->ip = $ip;
            $dataView->referer = $referer;
            $dataView->user_agent = $user_agent;
            $dataView->save();

            return redirect($data->url);
        } else {
            return redirect('/');
        }
    }
}

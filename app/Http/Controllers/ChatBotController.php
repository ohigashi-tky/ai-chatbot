<?php

namespace App\Http\Controllers;

use Aws\LexRuntimeV2\LexRuntimeV2Client;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');

        $client = new LexRuntimeV2Client([
            'region' => env('AWS_DEFAULT_REGION'),
            'version' => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $result = $client->recognizeText([
            'botId'     => env('AWS_LEX_BOT_ID'),
            'botAliasId' => env('AWS_LEX_BOT_ALIAS'),
            'localeId'   => env('AWS_LEX_BOT_LOCALE'),
            'sessionId' => session()->getId(),
            'text'      => $userMessage,
        ]);

        return response()->json([
            'reply' => $result['messages'][0]['content'] ?? 'エラーが発生しました。',
        ]);
    }
}

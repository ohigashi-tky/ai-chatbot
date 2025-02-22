<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');

        // Perplexity AI へリクエストを送信
        $response = $this->sendToPerplexity($userMessage);

        // 改行コードを変換（\n を <br> に変更）
        $formattedResponse = nl2br(e($response));

        return response()->json(['reply' => $formattedResponse]);
    }

    private function sendToPerplexity($message)
    {
        $apiUrl = "https://api.perplexity.ai/chat/completions";
        $apiKey = env('PERPLEXITY_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'model' => 'sonar-pro',
            'messages' => [
                ['role' => 'system', 'content' => 
                    '回答のルールは以下です。
                    - 日本語で300文字以内で改行を入れて読みやすくする。
                    - 日本語で最新のweb検索を行い、リアルタイムの情報を基に回答する。
                    - 参照を示す[1]などの数値は不要。'
                ],
                ['role' => 'user', 'content' => $message],
            ],
            'max_tokens' => 500,
            'temperature' => 0.7,
        ]);

        return $response->json()['choices'][0]['message']['content'] ?? "エラーが発生しました";
    }
}

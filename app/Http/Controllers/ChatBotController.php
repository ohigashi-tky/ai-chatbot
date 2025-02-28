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

    private function sendToPerplexity($userMessage)
    {
        $apiUrl = "https://api.perplexity.ai/chat/completions";
        $apiKey = env('PERPLEXITY_API_KEY');

        $systemMessage =
        "システム開発のプロとして、CTOのような役割で500文字以内で的確に技術相談などに対して回答してください。\n".
        "- 適度な改行を含めて、読みやすさを重視する。\n"
        ;

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'model' => 'sonar-pro',
            'messages' => [
                ['role' => 'system', 'content' => $systemMessage],
                ['role' => 'user', 'content' => $userMessage],
            ],
            'max_tokens' => 1000,
            'temperature' => 0.7,
        ]);

        return $response->json()['choices'][0]['message']['content'] ?? "エラーが発生しました";
    }
}

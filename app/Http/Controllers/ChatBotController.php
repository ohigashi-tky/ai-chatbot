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
        
        // レスポンスがグラフデータを含む配列の場合
        if (is_array($response) && isset($response['content'])) {
            // 改行コードを変換（\n を <br> に変更）
            $formattedResponse = nl2br(e($response['content']));
            
            return response()->json([
                'reply' => $formattedResponse,
                'chartData' => $response['chartData'] ?? null,
                'chartType' => $response['chartType'] ?? 'bar',
                'chartOptions' => $response['chartOptions'] ?? []
            ]);
        } else {
            // 通常のテキストレスポンスの場合
            $formattedResponse = nl2br(e($response));
            return response()->json(['reply' => $formattedResponse]);
        }
    }

    private function sendToPerplexity($userMessage)
    {
        $apiUrl = "https://api.perplexity.ai/chat/completions";
        $apiKey = env('PERPLEXITY_API_KEY');
    
        $systemMessage =
        "システム開発のプロとして、CTOのような役割で300文字以内で的確に技術相談などに対して回答してください。\n".
        "- 適度な改行を含めて、読みやすさを重視する。\n".
        "- 引用を表す[1]などの数値は回答に含まない。\n"
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
    
        // 検証用：グラフを表示するキーワードが含まれているか確認
        $content = $response->json()['choices'][0]['message']['content'] ?? "エラーが発生しました";
        
        // グラフ関連のキーワードが含まれている場合、グラフデータを追加
        if (str_contains(strtolower($userMessage), 'グラフ') || 
            str_contains(strtolower($userMessage), 'データ') || 
            str_contains(strtolower($userMessage), '分析') || 
            str_contains(strtolower($userMessage), '統計')) {
            
            // サンプルのグラフデータを生成
            $chartData = [
                'labels' => ['1月', '2月', '3月', '4月', '5月', '6月'],
                'datasets' => [
                    [
                        'label' => 'プロジェクト進捗率',
                        'data' => [12, 19, 35, 42, 56, 68],
                        'backgroundColor' => 'rgba(54, 162, 235, 0.9)',
                        'borderColor' => 'rgba(54, 162, 235, 1)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'バグ発生数',
                        'data' => [28, 22, 16, 12, 8, 5],
                        'backgroundColor' => 'rgba(255, 99, 132, 0.9)',
                        'borderColor' => 'rgba(255, 99, 132, 1)',
                        'borderWidth' => 1
                    ]
                ]
            ];

            // レスポンスに追加情報を含める
            return [
                'content' => $content,
                'chartData' => $chartData,
                'chartType' => 'bar',
                'chartOptions' => [
                    'plugins' => [
                        'title' => [
                            'display' => true,
                            'text' => 'プロジェクト進捗とバグ推移'
                        ]
                    ],
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true
                        ]
                    ]
                ]
            ];
        }

        return $content;
    }    
}

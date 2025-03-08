<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');

        $response = $this->sendToPerplexity($userMessage);
        
        // グラフを含む回答
        if (is_array($response) && isset($response['content'])) {
            // 改行コードを変換 \n → <br>
            $formattedResponse = nl2br(e($response['content']));
            
            return response()->json([
                'reply' => $formattedResponse,
                'chartData' => $response['chartData'] ?? null,
                'chartType' => $response['chartType'] ?? 'bar',
                'chartOptions' => $response['chartOptions'] ?? []
            ]);
        } else {
            // 通常の回答
            $formattedResponse = nl2br(e($response));
            return response()->json(['reply' => $formattedResponse]);
        }
    }

    private function sendToPerplexity($userMessage)
    {
        $apiUrl = "https://openrouter.ai/api/v1/chat/completions";
        $apiKey = env('OPEN_ROUTER_API_KEY');
        $aiModel = env('OPEN_ROUTER_AI_MODEL');
        $referer = 'ai-chatbot';
    
        $systemMessage = <<<EOT
            システム開発のプロとして、CTOのような役割で300文字以内で的確に技術相談などに対して回答してください。
            - 適度な改行を含めて、読みやすさを重視する。
            - 引用を表す[1]などの数値は回答に含まない。
            - 回答内容がデータの比較や時系列の変化、割合などを含む場合は、それを視覚的に表現できるグラフデータも提供してください。
            - グラフデータを提供する場合は、以下のJSON形式(```jsonというマークダウン記述を絶対に含まない)で回答の最後に追加してください:
            CHART_DATA:
            {
                "type": "bar|line|doughnut",
                "title": "グラフのタイトル",
                "labels": ["ラベル1", "ラベル2", ...],
                "datasets": [
                    {
                        "label": "データセット1の名前",
                        "data": [値1, 値2, ...]
                    },
                    {
                        "label": "データセット2の名前",
                        "data": [値1, 値2, ...]
                    }
                ]
            }
        EOT;

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => $referer,
            ])->post($apiUrl, [
                'model' => $aiModel,
                'messages' => [
                    ['role' => 'system', 'content' => $systemMessage],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => 1000,
                'temperature' => 0.7,
            ]);
        } catch (\Exception $e) {
            \Log::error("APIエラー: " . $response['error']['message']);
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? "エラーが発生しました";
        
        // CHART_DATA:がある場合、グラフデータを抽出
        if (preg_match('/CHART_DATA:\s*({.*})/s', $content, $matches)) {
            try {
                $chartDataJson = $matches[1];
                $chartInfo = json_decode($chartDataJson, true);
                
                if ($chartInfo && json_last_error() === JSON_ERROR_NONE) {
                    $content = preg_replace('/``````/s', '', $content);
                    $content = preg_replace('/CHART_DATA:[\s\S]*$/s', '', $content);
                    $content = trim($content);

                    $chartType = isset($chartInfo['type']) ? $chartInfo['type'] : 'bar';

                    $colors = [
                        ['rgba(54, 162, 235, 0.9)', 'rgba(54, 162, 235, 1)'],
                        ['rgba(255, 99, 132, 0.9)', 'rgba(255, 99, 132, 1)'],
                        ['rgba(75, 192, 192, 0.9)', 'rgba(75, 192, 192, 1)'],
                        ['rgba(255, 206, 86, 0.9)', 'rgba(255, 206, 86, 1)'],
                        ['rgba(153, 102, 255, 0.9)', 'rgba(153, 102, 255, 1)'],
                        ['rgba(255, 159, 64, 0.9)', 'rgba(255, 159, 64, 1)']
                    ];

                    $datasets = [];
                    foreach ($chartInfo['datasets'] as $index => $dataset) {
                        $colorIndex = $index % count($colors);
                        $datasets[] = [
                            'label' => $dataset['label'],
                            'data' => $dataset['data'],
                            'backgroundColor' => $colors[$colorIndex][0],
                            'borderColor' => $colors[$colorIndex][1],
                            'borderWidth' => 1
                        ];
                    }

                    $chartData = [
                        'labels' => $chartInfo['labels'],
                        'datasets' => $datasets
                    ];

                    return [
                        'content' => $content,
                        'chartData' => $chartData,
                        'chartType' => $chartType,
                        'chartOptions' => [
                            'plugins' => [
                                'title' => [
                                    'display' => true,
                                    'text' => $chartInfo['title'] ?? 'データ分析'
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
            } catch (\Exception $e) {
                // JSONパースエラーなどの例外処理
            }
        }

        return $content;
    }
}

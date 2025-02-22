## AIチャットボット
AIを使ったシンプルなチャットボット

## 技術スタック
Laravel  
PHP  
Vue  
Sonar Pro (Perplexity)

## 環境構築
SailでDockerコンテナ起動
```
./vendor/bin/sail up
```

依存関係のインストール
```
./vendor/bin/sail composer install
./vendor/bin/sail pnpm install
```

マイグレーション、シーディング
```
./vendor/bin/sail artisan migrate --seed
```

下記にアクセス
```
http://localhost
```
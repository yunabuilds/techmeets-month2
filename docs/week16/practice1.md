# 練習課題1：イメージサイズ削減

## 結果

| | before | after |
|---|---|---|
| イメージ名 | myapp:before | myapp:after |
| Dockerfile | docker/php/Dockerfile | docker/php/Dockerfile.prod |
| ベースイメージ | php:8.2-fpm（Debian） | php:8.2-fpm-alpine |
| サイズ | 816MB | 269MB |

**削減率：約67%（目標50%以上を達成）**

※ before はアプリのコードを含まない（開発時はボリュームで差し込むため）。
after はコード・vendor・ビルド済みCSS/JSを含んだうえでこのサイズ。

## 削減に効いた工夫

1. Alpine ベースに変更（Debian → Alpine）
2. マルチステージビルド（Composer・Node.js を最終イメージに含めない）
3. 開発用ツールを除外（pcov、git、composer --no-dev）
4. ビルド用の部品を削除（.build-deps の libpng-dev）
5. .dockerignore で不要なファイルを除外（送信量 約0.8MB）

## 一番効いた層（docker history より）

- before：PHP公式イメージ（Debian版）が入れているビルド用の道具の層が 366MB。
  OSの土台（87.7MB）と合わせて、土台だけで約520MB。
- after：Alpine版では土台が約85MB。ベースイメージの変更だけで約435MBの削減。
- 自分で入れたパッケージ：75.4MB → 6.07MB（git等を入れない、.build-deps でビルド用部品を削除）。
- after で最大の層は vendor（129MB）。--no-dev で開発用の部品は除外済み。
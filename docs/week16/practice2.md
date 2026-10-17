# 練習課題2：Trivy による脆弱性スキャンと修正

## スキャン結果（HIGH・CRITICAL のみ）

| 段階 | OS | vendor | 合計 | 結果ファイル |
|---|---|---|---|---|
| before（Debian・開発用） | 152 | ― | 152 | trivy-before.txt |
| after（Alpine・マルチステージ） | 0 | 1 | 1 | trivy-after.txt |
| after（Laravel 12 に更新後） | 0 | 0 | **0** | trivy-after-fixed.txt |

## 1. イメージの見直しで 152件 → 1件

before の脆弱性の多くは、本番では使わないソフトから出ていた。

- linux-libc-dev、libxml2-dev：ビルド用の部品
- openssh-client、perl：git と一緒に入ったもの
- curl：開発用 Dockerfile で入れたもの

after では Alpine
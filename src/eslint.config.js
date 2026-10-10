import js from '@eslint/js'
import globals from 'globals'

export default [
  js.configs.recommended,
  {
    files: ['resources/js/**/*.js'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: { ...globals.browser },
    },
    rules: {
      // 未使用変数をエラーに
      'no-unused-vars': 'error',
      // console.logを警告に
      'no-console': 'warn',
      // == ではなく === を強制
      'eqeqeq': 'error',
    },
  },
]
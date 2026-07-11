/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.php",
        "./includes/**/*.php",
        "./admin/**/*.php",
        "./student/**/*.php"
    ],
    theme: {
        extend: {
            colors: {
                // Digital Renaissance Palette
                'space-dark': '#000000',
                'space-light': '#000000',
                'space-medium': '#111111',
                'marble-white': '#FFFFFF',
                'marble-light': '#E8E4E0',
                'neon-cyan': '#00e5ff',
                'neon-blue': '#00b4d8',
                'neon-purple': '#7c3aed',
            },
            fontFamily: {
                'cinzel': ['Cinzel', 'Palatino Linotype', 'Book Antiqua', 'serif'],
                'inter': ['Inter', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'breathe': 'breathe 4s ease-in-out infinite',
                'pulse-slow': 'pulse 3s ease-in-out infinite',
                'spin-slow': 'spin 20s linear infinite',
                'drift': 'drift 30s linear infinite',
                'antigravity': 'antigravity 0.4s ease-out forwards',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                breathe: {
                    '0%, 100%': { transform: 'translateY(0px)', opacity: '1' },
                    '50%': { transform: 'translateY(-8px)', opacity: '0.95' },
                },
                drift: {
                    '0%': { transform: 'translate(0, 0) rotate(0deg)' },
                    '100%': { transform: 'translate(-50px, -30px) rotate(5deg)' },
                },
                antigravity: {
                    '0%': { transform: 'translateY(0) scale(1)' },
                    '100%': { transform: 'translateY(-20px) scale(1.02)' },
                }
            }
        }
    },
    plugins: [],
}

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ['./view/**/*.twig', './public/scripts/*.js'],
    darkMode: "class",

    theme: {
        extend: {
            colors: {
                "on-primary-container": "#a3faef",
                "primary-fixed": "#9cf2e8",
                "surface-cream": "#F8FAFC",
                "on-primary-fixed-variant": "#00504a",
                "on-background": "#111c2d",
                "outline": "#6e7977",
                "inverse-surface": "#263143",
                "on-tertiary-fixed-variant": "#72361b",
                "surface-tint": "#006a63",
                "tertiary-fixed-dim": "#ffb598",
                "secondary": "#505f76",
                "on-surface": "#111c2d",
                "primary-fixed-dim": "#80d5cb",
                "surface-container-high": "#dee8ff",
                "on-tertiary-fixed": "#370e00",
                "on-error": "#ffffff",
                "error": "#ba1a1a",
                "surface-container": "#e7eeff",
                "surface-bright": "#f9f9ff",
                "tertiary": "#7f4025",
                "outline-variant": "#bdc9c6",
                "on-secondary": "#ffffff",
                "on-primary-fixed": "#00201d",
                "on-secondary-fixed-variant": "#38485d",
                "tertiary-fixed": "#ffdbce",
                "on-tertiary": "#ffffff",
                "on-surface-variant": "#3e4947",
                "secondary-fixed": "#d3e4fe",
                "surface-container-highest": "#d8e3fb",
                "primary": "#005c55",
                "inverse-on-surface": "#ecf1ff",
                "surface-dim": "#cfdaf2",
                "surface": "#f9f9ff",
                "secondary-fixed-dim": "#b7c8e1",
                "on-error-container": "#93000a",
                "secondary-container": "#d0e1fb",
                "on-secondary-fixed": "#0b1c30",
                "on-secondary-container": "#54647a",
                "error-container": "#ffdad6",
                "surface-variant": "#d8e3fb",
                "on-tertiary-container": "#ffe5db",
                "tertiary-container": "#9c573a",
                "on-primary": "#ffffff",
                "inverse-primary": "#80d5cb",
                "surface-container-low": "#f0f3ff",
                "surface-container-lowest": "#ffffff",
                "background": "#f9f9ff",
                "primary-container": "#0f766e"
            },

            borderRadius: {
                DEFAULT: "0.25rem",
                lg: "0.5rem",
                xl: "0.75rem",
                full: "9999px"
            },

            spacing: {
                "space-xs": "0.25rem",
                "margin-mobile": "1rem",
                margin: "2rem",
                gutter: "1.25rem",
                "space-sm": "0.5rem",
                "gutter-mobile": "0.75rem",
                "space-lg": "1.5rem",
                "space-md": "1rem",
                "space-xl": "2.5rem"
            },

            fontFamily: {
                "label-sm": ["JetBrains Mono"],
                "grid-digit-sm": ["JetBrains Mono"],
                "grid-digit-lg": ["JetBrains Mono"],
                "headline-xl": ["Space Grotesk"],
                "label-md": ["JetBrains Mono"],
                "body-md": ["Inter"],
                "headline-md": ["Space Grotesk"],
                "headline-xl-mobile": ["Space Grotesk"],
                "body-lg": ["Inter"],
                "body-sm": ["Inter"],
                "headline-lg": ["Space Grotesk"]
            },

            fontSize: {
                "label-sm": [
                    "11px",
                    {
                        lineHeight: "14px",
                        letterSpacing: "0.06em",
                        fontWeight: "500"
                    }
                ],

                "grid-digit-sm": [
                    "11px",
                    {
                        lineHeight: "12px",
                        fontWeight: "500"
                    }
                ],

                "grid-digit-lg": [
                    "28px",
                    {
                        lineHeight: "32px",
                        fontWeight: "600"
                    }
                ],

                "headline-xl": [
                    "36px",
                    {
                        lineHeight: "44px",
                        letterSpacing: "-0.02em",
                        fontWeight: "600"
                    }
                ],

                "label-md": [
                    "13px",
                    {
                        lineHeight: "16px",
                        letterSpacing: "0.04em",
                        fontWeight: "500"
                    }
                ],

                "body-md": [
                    "15px",
                    {
                        lineHeight: "22px",
                        fontWeight: "400"
                    }
                ],

                "headline-md": [
                    "22px",
                    {
                        lineHeight: "28px",
                        fontWeight: "500"
                    }
                ],

                "headline-xl-mobile": [
                    "28px",
                    {
                        lineHeight: "36px",
                        letterSpacing: "-0.01em",
                        fontWeight: "600"
                    }
                ],

                "body-lg": [
                    "18px",
                    {
                        lineHeight: "28px",
                        fontWeight: "400"
                    }
                ],

                "body-sm": [
                    "13px",
                    {
                        lineHeight: "18px",
                        fontWeight: "400"
                    }
                ],

                "headline-lg": [
                    "28px",
                    {
                        lineHeight: "36px",
                        letterSpacing: "-0.01em",
                        fontWeight: "600"
                    }
                ]
            }
        }
    }
};
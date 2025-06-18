/** @type {import('tailwindcss').Config} */

module.exports = {
    content: ['./app/Views/**/*.twig', './public/assets/js/*.js'],
    theme: {
        extend: {},
    },
    plugins: [],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: "rgb(37, 30, 64)",
                    100: "rgb(44, 34, 80)",
                    200: "rgb(50, 39, 95)",
                    300: "rgb(57, 44, 114)",
                    400: "rgb(68, 53, 146)",
                    500: "rgb(88, 66, 195)",
                    600: "rgb(110, 86, 207)",
                    700: "rgb(124, 102, 220)",
                    800: "rgb(158, 140, 252)",
                    900: "rgb(241, 238, 254)",
                },
                neutral: {
                    0: "rgb(3, 7, 18)",
                    50: "rgb(17, 24, 39)",
                    100: "rgb(31, 41, 55)",
                    200: "rgb(55, 65, 81)",
                    300: "rgb(75, 85, 99)",
                    400: "rgb(107, 114, 128)",
                    500: "rgb(156, 163, 175)",
                    600: "rgb(209, 213, 219)",
                    700: "rgb(229, 231, 235)",
                    800: "rgb(243, 244, 246)",
                    900: "rgb(249, 250, 251)",
                    950: "rgb(255, 255, 255)",
                },
                error: {
                    50: "rgb(60, 24, 26)",
                    100: "rgb(72, 26, 29)",
                    200: "rgb(84, 27, 31)",
                    300: "rgb(103, 30, 34)",
                    400: "rgb(130, 32, 37)",
                    500: "rgb(170, 36, 41)",
                    600: "rgb(229, 72, 77)",
                    700: "rgb(242, 85, 90)",
                    800: "rgb(255, 99, 105)",
                    900: "rgb(254, 236, 238)",
                },
                warning: {
                    50: "rgb(44, 33, 0)",
                    100: "rgb(53, 40, 0)",
                    200: "rgb(62, 48, 0)",
                    300: "rgb(73, 60, 0)",
                    400: "rgb(89, 74, 5)",
                    500: "rgb(112, 94, 0)",
                    600: "rgb(245, 217, 10)",
                    700: "rgb(255, 239, 92)",
                    800: "rgb(240, 192, 0)",
                    900: "rgb(255, 250, 209)",
                },
                success: {
                    50: "rgb(19, 40, 25)",
                    100: "rgb(22, 48, 29)",
                    200: "rgb(25, 57, 33)",
                    300: "rgb(29, 68, 39)",
                    400: "rgb(36, 85, 48)",
                    500: "rgb(47, 110, 59)",
                    600: "rgb(70, 167, 88)",
                    700: "rgb(85, 180, 103)",
                    800: "rgb(99, 193, 116)",
                    900: "rgb(229, 251, 235)",
                },
                "brand-primary": "rgb(110, 86, 207)",
                "default-font": "rgb(249, 250, 251)",
                "subtext-color": "rgb(156, 163, 175)",
                "neutral-border": "rgb(55, 65, 81)",
                black: "rgb(3, 7, 18)",
                "default-background": "rgb(3, 7, 18)",
            },
            fontSize: {
                caption: [
                    "12px",
                    {
                        lineHeight: "16px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                "caption-bold": [
                    "12px",
                    {
                        lineHeight: "16px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                body: [
                    "14px",
                    {
                        lineHeight: "20px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                "body-bold": [
                    "14px",
                    {
                        lineHeight: "20px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                "heading-3": [
                    "16px",
                    {
                        lineHeight: "20px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                "heading-2": [
                    "20px",
                    {
                        lineHeight: "24px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                "heading-1": [
                    "30px",
                    {
                        lineHeight: "36px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
                "monospace-body": [
                    "14px",
                    {
                        lineHeight: "20px",
                        fontWeight: "400",
                        letterSpacing: "0em",
                    },
                ],
            },
            fontFamily: {
                caption: "Roboto Flex",
                "caption-bold": "Roboto Flex",
                body: "Roboto Flex",
                "body-bold": "Roboto Flex",
                "heading-3": "Roboto Flex",
                "heading-2": "Roboto Flex",
                "heading-1": "Roboto Flex",
                "monospace-body": "monospace",
            },
            boxShadow: {
                sm: "0px 1px 2px 0px rgba(0, 0, 0, 0.05)",
                default: "0px 1px 2px 0px rgba(0, 0, 0, 0.05)",
                md: "0px 4px 16px -2px rgba(0, 0, 0, 0.08), 0px 2px 4px -1px rgba(0, 0, 0, 0.08)",
                lg: "0px 12px 32px -4px rgba(0, 0, 0, 0.08), 0px 4px 8px -2px rgba(0, 0, 0, 0.08)",
                overlay:
                    "0px 12px 32px -4px rgba(0, 0, 0, 0.08), 0px 4px 8px -2px rgba(0, 0, 0, 0.08)",
            },
            borderRadius: {
                sm: "4px",
                md: "8px",
                DEFAULT: "8px",
                lg: "12px",
                full: "9999px",
            },
            container: {
                padding: {
                    DEFAULT: "16px",
                    sm: "calc((100vw + 16px - 640px) / 2)",
                    md: "calc((100vw + 16px - 768px) / 2)",
                    lg: "calc((100vw + 16px - 1024px) / 2)",
                    xl: "calc((100vw + 16px - 1280px) / 2)",
                    "2xl": "calc((100vw + 16px - 1536px) / 2)",
                },
            },
            spacing: {
                112: "28rem",
                144: "36rem",
                192: "48rem",
                256: "64rem",
                320: "80rem",
            },
            screens: {
                mobile: {
                    max: "767px",
                },
            },
        },
    },
}
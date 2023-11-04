module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                'ubuntu': 'ubuntu',
                'nexa': 'Nexa',
            },
            fontSize: {
                '48': '48px',
                '35': '35px',
                '22': '22px',
                '18': '18px',
                '16': '16px',
                '15': '15px',
                '13': '13px',
                '12': '12px',
            },
            color: {
                primary: {
                    'astronaut-blue': '#063F5F',
                    'ball-blue': '#16A9B7',
                    'dusty-orange': '#E97D32'
                },
                secondary: {
                    'columbia-blue': '#9FDCF8',
                    'dark-turquoise': '#42BEAF',
                    'jasmine': '#FFDE86',
                },
                1: {
                    1: '#D3E3EC',
                    2: '#87B1CB',
                    3: '#4C85A7',
                    4: '#2C5B7D',
                    5: '#093E60',
                },
                2: {
                    1: '#C6E9F2',
                    2: '#92D6E8',
                    3: '#61C4DD',
                    4: '#1DA9CC',
                    5: '#19849E',
                },
                3: {
                    1: '#FEF1E7',
                    2: '#F8D3BA',
                    3: '#F5B88D',
                    4: '#F39C63',
                    5: '#EF8239',
                },
                4: {
                    1: '#E9F7FE',
                    2: '#D2EEFC',
                    3: '#BEE7FB',
                    4: '#A9E0FA',
                    5: '#8BC5E1',
                },
                5: {
                    1: '#C2E7EB',
                    2: '#A8DDE3',
                    3: '#9CDAE5',
                    4: '#55C5CD',
                    5: '#47C2CA',
                },
                6: {
                    1: '#FCF3D3',
                    2: '#FBEFC4',
                    3: '#F8E7A8',
                    4: '#F7DF8B',
                    5: '#DBC470',
                },
            },
            boxShadow: {
                '1': 'box-shadow: 8px 8px 0px #D3E3EC',
            }
        },
    },
    plugins: [],
};

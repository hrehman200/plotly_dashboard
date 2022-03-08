<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title>Dashboard</title>
    <!-- Plotly.js -->
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/util.js"></script>
    <style>
        .btn.active {
            filter: brightness(85%) !important;
        }

        div.tooltip {
            position: absolute;
            min-width: 100px;
            max-width: 450px;
            height: auto;
            padding: 5px;
            font: 14px Verdana;
            background: white;
            border: 1px solid grey;
            pointer-events: none;
            opacity: 1;
            display: none;
        }

        body {
            font-family: Verdana !important;
        }

        .tabBtns .btn {
            margin-bottom: 2px !important;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-md-12">
                <nav class="nav nav-pills justify-content-center tabBtns">
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="graph" style="min-height:500px;"></div>
                <div class="tooltip"></div>
            </div>
        </div>
    </div>
    <script>
        var triadsConfig = [];
        var rawData = [];

        Plotly.d3.json("config.json", function(data) {
            triadsConfig = data.triads;

            initiate();
        });

        function initiate() {
            Plotly.d3.csv("data/initiation.csv", function(data) {
                rawData = data;
                //processData(data)
            });

            for (let i = 0; i < triadsConfig.length; i++) {
                $('.tabBtns').append('<a href="javascript:;" data-index="' + i + '" class="btn btn-sm btn-info btn-t">Triad ' + (i + 1) + '</a>')
            }

            $('body').on('click', '.tabBtns .btn-t, .tabBtns .btn-b', function(e) {
                e.preventDefault();
                $('.tabBtns .btn').removeClass('active');
                $(this).addClass('active');
                if ($(this).hasClass('btn-t')) {
                    drawTriad($(this).attr('data-index'));
                }
            });


            setTimeout(function() {$('.btn-t:eq(0)').trigger('click')}, 1000) 
        }

        function drawTriad(triadIndex) {
            arrangeDataForTriad(triadIndex)

            let keys = [triadsConfig[triadIndex].label1, triadsConfig[triadIndex].label2, triadsConfig[triadIndex].label3];

            Plotly.newPlot('graph', [{
                type: 'scatterternary',
                mode: 'markers',
                a: rawData.map(function(d) {
                    return d[keys[0]];
                }),
                b: rawData.map(function(d) {
                    return d[keys[1]];
                }),
                c: rawData.map(function(d) {
                    return d[keys[2]];
                }),
                text: rawData.map(function(d) {
                    return 'HARIS';
                }),
                marker: {
                    symbol: 0,
                    color: 'red',
                    size: 9,
                    line: {
                        color: 'black',
                        width: 1
                    },
                },
                hoverinfo: 'none'
            }], {
                ternary: {
                    sum: 100,
                    aaxis: makeAxis(keys[0], 0),
                    baxis: makeAxis(keys[1], 45),
                    caxis: makeAxis(keys[2], -45),
                    bgcolor: '#eee'
                },
                paper_bgcolor: '#eee',
                title: {
                    text: triadsConfig[triadIndex].title,
                    font: {
                        family: 'Verdana',
                        size: 16
                    },
                    xref: 'paper',
                    x: 0,
                    y: -10
                },
                dragmode: 'select',
                height: 500,
                margin: {
                    b: 100
                }
            }, {
                displaylogo: false,
                modeBarButtonsToRemove: ['toggleHover'],
                responsive: true
            });
        }

        function makeAxis(title, tickangle) {
            return {
                title: title,
                titlefont: {
                    size: 15,
                    family: 'Verdana'
                },
                tickangle: tickangle,
                tickfont: {
                    size: 15
                },
                tickcolor: 'rgba(0,0,0,0)',
                ticklen: 5,
                showticklabels: false,
                showline: true,
                showgrid: false,
                fixedrange: true
            };
        }

        function arrangeDataForTriad(index) {

            for (let row of rawData) {
                row[triadsConfig[index].label1] = row[triadsConfig[index].label1 + ':' + triadsConfig[index].title]
                row[triadsConfig[index].label2] = row[triadsConfig[index].label2 + ':' + triadsConfig[index].title]
                row[triadsConfig[index].label3] = row[triadsConfig[index].label3 + ':' + triadsConfig[index].title]
            }

            console.log(rawData)
        }
    </script>
</body>
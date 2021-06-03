<!DOCTYPE html>
<html>

<head>
    <title>MOSUBA (Monitoring Suhu dan Kelembaban)</title>
    <link rel="stylesheet" href="assets/stylesheet.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="#temperature">Suhu</a></li>
                <li><a href="#humidity">Kelembaban</a></li>
                <li><a href="#about">Tentang Aplikasi</a></li>
            </ul>
        </nav>
        <div class="jumbotron">
            <h1>MOSUBA</h1>
            <p>Monitoring Suhu dan Kelembaban</p>
        </div>
    </header>
    <main>
        <div id="temperature" class="container card">
            <div class="parameter1">
                <p class="numberTemp" id="temperatureValue">0</p>
                <p>Suhu (&#176;C)</p>
            </div>
            <div class="chart">
                <canvas id="tempChart" style="margin: 30px 0; height: 450px;"> </canvas>
            </div>
        </div>
        <div id="humidity" class="container card">
            <div class="parameter2">
                <p class="numberHum" id="humidityValue">0</p>
                <p>Kelembaban Udara (&#37;)</p>
            </div>
            <div class="chart">
                <canvas id="humChart" style="margin: 30px 0; height: 450px;"> </canvas>
            </div>
        </div>
        <div id="about">
            <p>Aplikasi ini dibuat untuk keperluan memenuhi tugas Final Project yang mana dalam aplikasi ini kami membuat sebuah sistem yang dapat melaporkan kondisi suhu dalam suatu ruangan yang didalamnya telah ditempatkan sebuah alat pendeteksi suhu yang akan bekerja secara realtime</p>
            <div class="team">
                <p>Team</p>
                <p>A. Muh. Irsyad Baso</p>
                <p>Amiruddin</p>
                <p>Anugrah Septiansyah</p>
                <p>Irfandi Kurniawan Anwar</p>
                <p>Muh. Ilham Askari</p>
            </div>
        </div>
    </main>
    <footer>
        <p>Topik Khusus Perangkat Lunak || 2021</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        window.onload = function() {
            console.log('LOADED')

            // Deklarasi Global Variabel untuk Real-time Chart
            var updateInterval = 10000 // milisecond
            var numberElement = 100
            var updateCount = 0

            // Deklarasi Variabel Chart dan Opsi Chart
            var tempChart = $('#tempChart')
            var humChart = $('#humChart')
            var commonOpt = {
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            displayFormats: {
                                second: 'hh:mm:ss'
                            }
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    enabled: false
                }
            }

            // Inisiasi Chart Suhu dengan Nilai Default 0
            var tempChartInstance = new Chart(tempChart, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Suhu',
                        data: 0,
                        fill: true,
                        borderColor: '#343e9a',
                        borderWidth: 1
                    }]
                },
                options: Object.assign({}, commonOpt, {
                    title: {
                        display: true,
                        text: 'Suhu',
                        fontSize: 16
                    }
                })
            })

            // Inisiasi Chart Kelembabab dengan Nilai Default 0
            var humChartInstance = new Chart(humChart, {
                type: 'line',
                data: {
                    datasets: [{
                        label: 'Kelembaban',
                        data: 0,
                        fill: true,
                        borderColor: '#343e9a',
                        borderWidth: 1
                    }]
                },
                options: Object.assign({}, commonOpt, {
                    title: {
                        display: true,
                        text: 'Kelembaban',
                        fontSize: 16
                    }
                })
            })

            // Menambahkan Nilai 0 Jika Menit dan Detik Dibawah 10
            function checkTime(i) {
                if (i < 10) {
                    i = "0" + i;
                }
                return i;
            }

            function addChartData(data) {
                // Deklarasi Variabel untuk Garis X Chart
                var today = new Date()
                var hours = today.getHours()
                var minutes = today.getMinutes()
                var seconds = today.getSeconds()
                minutes = checkTime(minutes)
                seconds = checkTime(seconds)
                xLabel = hours + ":" + minutes + ":" + seconds

                // Menambahkan Waktu Sekarang dan Nilai Data Baru ke Dalam Chart
                if (data) {
                    tempChartInstance.data.labels.push(xLabel)
                    tempChartInstance.data.datasets.forEach((dataset) => (dataset.data.push(data['temperature'])))

                    humChartInstance.data.labels.push(xLabel)
                    humChartInstance.data.datasets.forEach((dataset) => (dataset.data.push(data['humidity'])))
                    // console.log(`id: ${data.id}, Temperature: ${data.temperature}, Humidity: ${data.humidity}`)
                }
                // Pengecekan Jika Data Chart Melebihi 100, Maka Data Pertama Dikeluarkan
                if (updateCount > numberElement) {
                    tempChartInstance.data.labels.shift()
                    tempChartInstance.data.datasets[0].data.shift()

                    humChartInstance.data.labels.shift()
                    humChartInstance.data.datasets[0].data.shift()
                } else updateCount++

                // Update Data Chart
                tempChartInstance.update()
                humChartInstance.update()
            }

            function updateData() {
                console.log('Updating Data')
                // Ambil Data dari Database dalam bentuk JSON dan Panggil Fungsi addChartData
                $.getJSON('get_data.php', addChartData)
                setTimeout(updateData, updateInterval)
            }

            // Sesuaikan Nilai Suhu dan Kelembaban dengan Chart
            setInterval(function() {
                $('#temperatureValue').load('get_temp.php')
                $('#humidityValue').load('get_hum.php')
            }, updateInterval)

            updateData()
        }
    </script>
    <script>
        var base_url = 'http://localhost/tkrpl/' // Deklarasi URL Aplikasi 
        $(document).ready(function() {
            setInterval(function() {
                // Ambil Data dari Antares
                $.getJSON(base_url + 'api/get-antares-data.php', function(datas) {
                    $.each(datas, function(key, val) {
                        // Lalu Insert ke Database
                        $.ajax({
                            url: base_url + 'api/post-data.php',
                            method: "POST",
                            data: {
                                temperature: val.temperature,
                                humidity: val.humidity
                            },
                            success: function(data) {
                                console.log('Sukses')
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                // console.log(xhr.status);
                                // console.log(xhr.responseText);
                                // console.log(thrownError);
                            },
                        })
                    })
                })
            }, 10000)
        })
    </script>
</body>

</html>
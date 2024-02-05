<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Capres-Cawapres 2024</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="max-w-screen-lg mx-auto px-4 bg-slate-100">
    <section class="py-16">
        <h1 class="font-semibold text-3xl uppercase text-center">Daftar Capres-Cawapres 2024</h1>
    </section>

    <section>
        <h2 class="font-semibold text-2xl">Capres</h2>
        <div class="grid grid-cols-3 gap-6 mt-3">
            @foreach ($presidentialCandidates as $candidate)
                <div class="bg-white rounded-xl p-6">
                    <div class="bg-slate-200 rounded-full flex justify-center items-center size-8 text-lg mb-3">
                        {{ $candidate->nomor_urut }}
                    </div>
                    <h3 class="inline-block font-semibold text-lg">
                        {{ $candidate->nama_lengkap }}
                    </h3>
                    <h4>Tempat, Tanggal lahir:</h4>
                    <p>{{ $candidate->tempat_lahir }},
                        {{ \Carbon\Carbon::parse($candidate->tanggal_lahir)->translatedFormat('d M Y', 'id') }}</p>
                    <h4>Usia:</h4>
                    <p>{{ $candidate->usia }}</p>
                    <h4>Karir:</h4>
                    <ul>
                        @foreach ($candidate->karir as $karir)
                            <li>{{ $karir->jabatan }} <br>{{ $karir->tahun_mulai }} -
                                {{ $karir->tahun_selesai ?? 'Sekarang' }}
                            </li>
                        @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="pt-4 pb-16">
        <h2 class="font-semibold text-2xl">Cawapres</h2>
        <div class="grid grid-cols-3 gap-6 mt-3">
            @foreach ($vicePresidentialCandidates as $candidate)
                <div class="bg-white rounded-xl p-6">
                    <div class="bg-slate-200 rounded-full flex justify-center items-center size-8 text-lg mb-3">
                        {{ $candidate->nomor_urut }}
                    </div>
                    <h3 class="inline-block font-semibold text-lg">
                        {{ $candidate->nama_lengkap }}
                    </h3>
                    <h4>Tempat, Tanggal lahir:</h4>
                    <p>{{ $candidate->tempat_lahir }},
                        {{ \Carbon\Carbon::parse($candidate->tanggal_lahir)->translatedFormat('d M Y', 'id') }}</p>
                    <h4>Usia:</h4>
                    <p>{{ $candidate->usia }}</p>
                    <h4>Karir:</h4>
                    <ul>
                        @foreach ($candidate->karir as $karir)
                            <li>{{ $karir->jabatan }} <br>{{ $karir->tahun_mulai }} -
                                {{ $karir->tahun_selesai ?? 'Sekarang' }}
                            </li>
                        @endforeach
                </div>
            @endforeach
        </div>
    </section>
</body>

</html>

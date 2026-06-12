<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Belanja NusaRasa</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1C1208;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #1C1208;
            padding-bottom: 20px;
        }
        .title {
            font-size: 32px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 10px 0;
        }
        .title span {
            color: #6D28D9;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .checklist {
            margin-bottom: 40px;
        }
        .checklist table {
            width: 100%;
            border-collapse: collapse;
        }
        .checklist td {
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
            vertical-align: top;
        }
        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #1C1208;
            margin-right: 10px;
            position: relative;
            top: 2px;
        }
        .count-badge {
            display: inline-block;
            padding: 2px 6px;
            background-color: #6D28D9;
            color: white;
            font-size: 10px;
            border-radius: 4px;
            margin-left: 5px;
            font-weight: bold;
        }
        
        .recipes-grid {
            width: 100%;
        }
        .recipe-card {
            border: 1px solid #1C1208;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .recipe-header {
            background-color: #6D28D9;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
        }
        .recipe-title {
            font-size: 14px;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }
        .recipe-meta {
            font-size: 10px;
            color: #E9D5FF;
        }
        .ingredients-list {
            padding: 10px 15px;
            font-size: 12px;
            background-color: #F8F5F0;
        }
        .ingredients-list div {
            margin-bottom: 5px;
        }
        .bullet {
            display: inline-block;
            width: 4px;
            height: 4px;
            background-color: #6D28D9;
            border-radius: 50%;
            margin-right: 5px;
            position: relative;
            top: -2px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1 class="title">Daftar <span>Belanja</span></h1>
        @php
            $dayLabels = ['monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu', 'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'];
            $selectedLabels = collect($selectedDays)->map(fn($d) => $dayLabels[$d] ?? $d)->join(', ');
        @endphp
        <p class="subtitle">{{ $selectedLabels }} · {{ count($shoppingList['flat_list']) }} bahan unik</p>
    </div>

    <!-- Consolidated checklist -->
    <div class="checklist">
        <h2 class="section-title">🛒 Semua Bahan</h2>
        @if(empty($shoppingList['flat_list']))
            <p>Tidak ada bahan yang ditemukan.</p>
        @else
            <table>
                @php
                    // Chunk into columns for display
                    $chunks = array_chunk($shoppingList['flat_list'], ceil(count($shoppingList['flat_list']) / 2));
                @endphp
                <tr>
                    <td style="width: 50%; border: none;">
                        @if(isset($chunks[0]))
                            @foreach($chunks[0] as $item)
                                <div style="margin-bottom: 10px;">
                                    <span class="checkbox"></span>
                                    {{ $item['original'] }}
                                    @if($item['count'] > 1)
                                        <span class="count-badge">×{{ $item['count'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td style="width: 50%; border: none;">
                        @if(isset($chunks[1]))
                            @foreach($chunks[1] as $item)
                                <div style="margin-bottom: 10px;">
                                    <span class="checkbox"></span>
                                    {{ $item['original'] }}
                                    @if($item['count'] > 1)
                                        <span class="count-badge">×{{ $item['count'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                </tr>
            </table>
        @endif
    </div>

    <!-- Per-recipe breakdown -->
    @if(!empty($shoppingList['recipes']))
        <div class="recipes-breakdown">
            <h2 class="section-title">📋 Detail Bahan per Resep</h2>
            
            <div class="recipes-grid">
                @foreach($shoppingList['recipes'] as $recipeItem)
                    <div class="recipe-card">
                        <div class="recipe-header">
                            <h3 class="recipe-title">{{ $recipeItem['title'] }}</h3>
                            <div class="recipe-meta">{{ $recipeItem['day'] }} · {{ $recipeItem['meal_type'] }}</div>
                        </div>
                        <div class="ingredients-list">
                            @foreach($recipeItem['ingredients'] as $ingredient)
                                <div><span class="bullet"></span> {{ $ingredient }}</div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</body>
</html>

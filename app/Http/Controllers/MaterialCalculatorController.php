<?php

namespace App\Http\Controllers;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialCalculatorController extends Controller
{
    public function index()
    {
        return view('customer.calculator.index');
    }

    public function calculatePaint(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'length' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'height' => 'required|numeric|min:0.1',
            'doors' => 'required|integer|min:0',
            'windows' => 'required|integer|min:0',
            'coats' => 'required|integer|min:1|max:5',
            'coverage_per_liter' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $length = $request->length;
        $width = $request->width;
        $height = $request->height;
        $doors = $request->doors;
        $windows = $request->windows;
        $coats = $request->coats;
        $coveragePerLiter = $request->coverage_per_liter;

        // Calculate areas
        $wallArea = 2 * ($length * $height + $width * $height);
        $ceilingArea = $length * $width;
        $doorArea = $doors * 2.1 * 0.9; // Standard door size
        $windowArea = $windows * 1.2 * 1.0; // Standard window size
        
        $totalArea = $wallArea + $ceilingArea - $doorArea - $windowArea;
        $paintNeeded = ($totalArea * $coats) / $coveragePerLiter;
        
        // Add 10% wastage factor
        $paintWithWastage = $paintNeeded * 1.1;

        return response()->json([
            'success' => true,
            'results' => [
                'wall_area' => round($wallArea, 2),
                'ceiling_area' => round($ceilingArea, 2),
                'door_area' => round($doorArea, 2),
                'window_area' => round($windowArea, 2),
                'net_area' => round($totalArea, 2),
                'paint_needed' => round($paintNeeded, 2),
                'paint_with_wastage' => round($paintWithWastage, 2),
                'recommended_buckets' => ceil($paintWithWastage)
            ]
        ]);
    }

    public function calculateTiles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'room_length' => 'required|numeric|min:0.1',
            'room_width' => 'required|numeric|min:0.1',
            'tile_length' => 'required|numeric|min:0.01',
            'tile_width' => 'required|numeric|min:0.01',
            'wastage_percent' => 'required|numeric|min:0|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $roomArea = $request->room_length * $request->room_width;
        $tileArea = $request->tile_length * $request->tile_width;
        $tilesNeeded = $roomArea / $tileArea;
        $wastage = $request->wastage_percent / 100;
        $tilesWithWastage = $tilesNeeded * (1 + $wastage);

        return response()->json([
            'success' => true,
            'results' => [
                'room_area' => round($roomArea, 2),
                'tile_area' => round($tileArea, 4),
                'tiles_needed' => ceil($tilesNeeded),
                'tiles_with_wastage' => ceil($tilesWithWastage),
                'total_boxes' => ceil($tilesWithWastage / 10) // Assuming 10 tiles per box
            ]
        ]);
    }

    public function calculateBricks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'wall_length' => 'required|numeric|min:0.1',
            'wall_height' => 'required|numeric|min:0.1',
            'brick_length' => 'required|numeric|min:0.01',
            'brick_height' => 'required|numeric|min:0.01',
            'mortar_thickness' => 'required|numeric|min:0.001',
            'doors' => 'required|integer|min:0',
            'windows' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $wallArea = $request->wall_length * $request->wall_height;
        $doorArea = $request->doors * 2.1 * 0.9;
        $windowArea = $request->windows * 1.2 * 1.0;
        $netWallArea = $wallArea - $doorArea - $windowArea;

        $brickWithMortarLength = $request->brick_length + $request->mortar_thickness;
        $brickWithMortarHeight = $request->brick_height + $request->mortar_thickness;
        $brickArea = $brickWithMortarLength * $brickWithMortarHeight;
        
        $bricksNeeded = $netWallArea / $brickArea;
        $bricksWithWastage = $bricksNeeded * 1.05; // 5% wastage

        return response()->json([
            'success' => true,
            'results' => [
                'wall_area' => round($wallArea, 2),
                'net_wall_area' => round($netWallArea, 2),
                'bricks_needed' => ceil($bricksNeeded),
                'bricks_with_wastage' => ceil($bricksWithWastage)
            ]
        ]);
    }

    public function calculateConcrete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'length' => 'required|numeric|min:0.1',
            'width' => 'required|numeric|min:0.1',
            'thickness' => 'required|numeric|min:0.01',
            'concrete_ratio' => 'required|string|in:1:2:4,1:1.5:3,1:3:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $volume = $request->length * $request->width * $request->thickness;
        $volumeWithWastage = $volume * 1.1; // 10% wastage

        // Calculate materials based on ratio
        $ratios = [
            '1:2:4' => ['cement' => 1, 'sand' => 2, 'aggregate' => 4],
            '1:1.5:3' => ['cement' => 1, 'sand' => 1.5, 'aggregate' => 3],
            '1:3:6' => ['cement' => 1, 'sand' => 3, 'aggregate' => 6]
        ];

        $ratio = $ratios[$request->concrete_ratio];
        $totalRatio = array_sum($ratio);
        
        // Material calculations per cubic meter
        $cementBags = ($volumeWithWastage * $ratio['cement'] / $totalRatio) * 28.8; // 28.8 bags per m³
        $sandCubicMeters = ($volumeWithWastage * $ratio['sand'] / $totalRatio);
        $aggregateCubicMeters = ($volumeWithWastage * $ratio['aggregate'] / $totalRatio);

        return response()->json([
            'success' => true,
            'results' => [
                'volume' => round($volume, 3),
                'volume_with_wastage' => round($volumeWithWastage, 3),
                'cement_bags' => ceil($cementBags),
                'sand_cubic_meters' => round($sandCubicMeters, 2),
                'aggregate_cubic_meters' => round($aggregateCubicMeters, 2)
            ]
        ]);
    }
}
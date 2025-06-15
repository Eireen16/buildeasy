@extends('layouts.customer')

@section('content')
<div class="container-fluid mt-3" style="background-color: #a8d8e8; padding: 20px 0;">
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-primary">
                    <h4 class="mb-0"><i class="fas fa-calculator me-2"></i>Material Calculator</h4>
                    <p class="mb-0 text-primary">Calculate the estimate amount of materials needed for your construction project</p>
                </div>
                <div class="card-body">
                    <!-- Calculator Tabs -->
                    <ul class="nav nav-tabs mb-4" id="calculatorTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="paint-tab" data-bs-toggle="tab" data-bs-target="#paint" type="button" role="tab">
                                <i class="fas fa-paint-roller me-2"></i>Paint Calculator
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tiles-tab" data-bs-toggle="tab" data-bs-target="#tiles" type="button" role="tab">
                                <i class="fas fa-th-large me-2"></i>Tiles Calculator
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bricks-tab" data-bs-toggle="tab" data-bs-target="#bricks" type="button" role="tab">
                                <i class="fas fa-cubes me-2"></i>Bricks Calculator
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="concrete-tab" data-bs-toggle="tab" data-bs-target="#concrete" type="button" role="tab">
                                <i class="fas fa-truck-loading me-2"></i>Concrete Calculator
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="calculatorTabContent">
                        <!-- Paint Calculator -->
                        <div class="tab-pane fade show active" id="paint" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="mb-3">Room Dimensions</h5>
                                    <form id="paintForm">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Length (m)</label>
                                                <input type="number" class="form-control" name="length" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Width (m)</label>
                                                <input type="number" class="form-control" name="width" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Height (m)</label>
                                                <input type="number" class="form-control" name="height" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Coverage per Liter (m²)</label>
                                                <input type="number" class="form-control" name="coverage_per_liter" value="12" step="0.1" min="1" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Doors</label>
                                                <input type="number" class="form-control" name="doors" value="1" min="0" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Windows</label>
                                                <input type="number" class="form-control" name="windows" value="2" min="0" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Coats</label>
                                                <input type="number" class="form-control" name="coats" value="2" min="1" max="5" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-calculator me-2"></i>Calculate Paint
                                        </button>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <div id="paintResults" class="results-section" style="display: none;">
                                        <h5 class="mb-3">Calculation Results</h5>
                                        <div class="results-card">
                                            <!-- Results will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tiles Calculator -->
                        <div class="tab-pane fade" id="tiles" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="mb-3">Room & Tile Dimensions</h5>
                                    <form id="tilesForm">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Room Length (m)</label>
                                                <input type="number" class="form-control" name="room_length" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Room Width (m)</label>
                                                <input type="number" class="form-control" name="room_width" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tile Length (m)</label>
                                                <input type="number" class="form-control" name="tile_length" step="0.01" min="0.01" value="0.6" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Tile Width (m)</label>
                                                <input type="number" class="form-control" name="tile_width" step="0.01" min="0.01" value="0.6" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Wastage (%)</label>
                                                <input type="number" class="form-control" name="wastage_percent" value="10" min="0" max="50" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-calculator me-2"></i>Calculate Tiles
                                        </button>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <div id="tilesResults" class="results-section" style="display: none;">
                                        <h5 class="mb-3">Calculation Results</h5>
                                        <div class="results-card">
                                            <!-- Results will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bricks Calculator -->
                        <div class="tab-pane fade" id="bricks" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="mb-3">Wall & Brick Dimensions</h5>
                                    <form id="bricksForm">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Wall Length (m)</label>
                                                <input type="number" class="form-control" name="wall_length" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Wall Height (m)</label>
                                                <input type="number" class="form-control" name="wall_height" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Brick Length (m)</label>
                                                <input type="number" class="form-control" name="brick_length" step="0.001" min="0.01" value="0.19" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Brick Height (m)</label>
                                                <input type="number" class="form-control" name="brick_height" step="0.001" min="0.01" value="0.09" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Mortar Thickness (m)</label>
                                                <input type="number" class="form-control" name="mortar_thickness" step="0.001" min="0.001" value="0.01" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Doors</label>
                                                <input type="number" class="form-control" name="doors" value="0" min="0" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Windows</label>
                                                <input type="number" class="form-control" name="windows" value="0" min="0" required>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-calculator me-2"></i>Calculate Bricks
                                        </button>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <div id="bricksResults" class="results-section" style="display: none;">
                                        <h5 class="mb-3">Calculation Results</h5>
                                        <div class="results-card">
                                            <!-- Results will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Concrete Calculator -->
                        <div class="tab-pane fade" id="concrete" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h5 class="mb-3">Concrete Slab Dimensions</h5>
                                    <form id="concreteForm">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Length (m)</label>
                                                <input type="number" class="form-control" name="length" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Width (m)</label>
                                                <input type="number" class="form-control" name="width" step="0.1" min="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Thickness (m)</label>
                                                <input type="number" class="form-control" name="thickness" step="0.01" min="0.01" value="0.1" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Concrete Ratio</label>
                                                <select class="form-control" name="concrete_ratio" required>
                                                    <option value="1:2:4">1:2:4 (Standard)</option>
                                                    <option value="1:1.5:3">1:1.5:3 (High Strength)</option>
                                                    <option value="1:3:6">1:3:6 (Lean Mix)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-calculator me-2"></i>Calculate Concrete
                                        </button>
                                    </form>
                                </div>
                                <div class="col-lg-6">
                                    <div id="concreteResults" class="results-section" style="display: none;">
                                        <h5 class="mb-3">Calculation Results</h5>
                                        <div class="results-card">
                                            <!-- Results will be populated here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
</div>

<style>
.results-section {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 20px;
}

.results-card {
    background: white;
    border-radius: 6px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.result-item {
    display: flex;
    justify-content: between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.result-item:last-child {
    border-bottom: none;
}

.result-label {
    font-weight: 500;
    color: #495057;
}

.result-value {
    font-weight: 600;
    color: #007bff;
}

.nav-tabs .nav-link {
    border: none;
    background: none;
    color: #6c757d;
    border-radius: 0;
    border-bottom: 3px solid transparent;
    padding: 12px 20px;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    border-bottom-color: #007bff;
    background: none;
}

.nav-tabs .nav-link:hover {
    color: #007bff;
    border-color: transparent;
}

.saved-project-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: white;
}

.saved-project-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 10px;
}

.project-calculations {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 10px;
    margin-top: 10px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store current calculations for saving
    let currentCalculations = {};

    // Paint Calculator
    document.getElementById('paintForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("calculator.paint") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPaintResults(data.results);
                currentCalculations.paint = {
                    inputs: Object.fromEntries(formData),
                    results: data.results,
                    type: 'paint'
                };
            } else {
                showError('Error calculating paint requirements');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while calculating');
        });
    });

    // Tiles Calculator
    document.getElementById('tilesForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("calculator.tiles") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayTilesResults(data.results);
                currentCalculations.tiles = {
                    inputs: Object.fromEntries(formData),
                    results: data.results,
                    type: 'tiles'
                };
            } else {
                showError('Error calculating tile requirements');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while calculating');
        });
    });

    // Bricks Calculator
    document.getElementById('bricksForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("calculator.bricks") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayBricksResults(data.results);
                currentCalculations.bricks = {
                    inputs: Object.fromEntries(formData),
                    results: data.results,
                    type: 'bricks'
                };
            } else {
                showError('Error calculating brick requirements');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while calculating');
        });
    });

    // Concrete Calculator
    document.getElementById('concreteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('{{ route("calculator.concrete") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayConcreteResults(data.results);
                currentCalculations.concrete = {
                    inputs: Object.fromEntries(formData),
                    results: data.results,
                    type: 'concrete'
                };
            } else {
                showError('Error calculating concrete requirements');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while calculating');
        });
    });

    // Load saved projects when tab is shown
    @auth
    document.getElementById('saved-tab').addEventListener('click', function() {
        loadSavedProjects();
    });
    @endauth

    // Display functions
    function displayPaintResults(results) {
        const html = `
            <div class="result-item">
                <span class="result-label">Wall Area:</span>
                <span class="result-value">${results.wall_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Ceiling Area:</span>
                <span class="result-value">${results.ceiling_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Net Area (after doors/windows):</span>
                <span class="result-value">${results.net_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Paint Needed:</span>
                <span class="result-value">${results.paint_needed} liters</span>
            </div>
            <div class="result-item">
                <span class="result-label">Paint with Wastage (10%):</span>
                <span class="result-value">${results.paint_with_wastage} liters</span>
            </div>
            <div class="result-item">
                <span class="result-label">Recommended Buckets:</span>
                <span class="result-value">${results.recommended_buckets} buckets</span>
            </div>
        `;
        document.querySelector('#paintResults .results-card').innerHTML = html;
        document.getElementById('paintResults').style.display = 'block';
    }

    function displayTilesResults(results) {
        const html = `
            <div class="result-item">
                <span class="result-label">Room Area:</span>
                <span class="result-value">${results.room_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Tile Area:</span>
                <span class="result-value">${results.tile_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Tiles Required:</span>
                <span class="result-value">${results.tiles_needed} tiles</span>
            </div>
            <div class="result-item">
                <span class="result-label">Tiles with Wastage:</span>
                <span class="result-value">${results.tiles_with_wastage} tiles</span>
            </div>
        `;
        document.querySelector('#tilesResults .results-card').innerHTML = html;
        document.getElementById('tilesResults').style.display = 'block';
    }

    function displayBricksResults(results) {
        const html = `
            <div class="result-item">
                <span class="result-label">Total Wall Area:</span>
                <span class="result-value">${results.wall_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Net Wall Area:</span>
                <span class="result-value">${results.net_wall_area} m²</span>
            </div>
            <div class="result-item">
                <span class="result-label">Bricks Required:</span>
                <span class="result-value">${results.bricks_needed} bricks</span>
            </div>
            <div class="result-item">
                <span class="result-label">Bricks with Wastage (5%):</span>
                <span class="result-value">${results.bricks_with_wastage} bricks</span>
            </div>
        `;
        document.querySelector('#bricksResults .results-card').innerHTML = html;
        document.getElementById('bricksResults').style.display = 'block';
    }

    function displayConcreteResults(results) {
        const html = `
            <div class="result-item">
                <span class="result-label">Concrete Volume:</span>
                <span class="result-value">${results.volume} m³</span>
            </div>
            <div class="result-item">
                <span class="result-label">Volume with Wastage (10%):</span>
                <span class="result-value">${results.volume_with_wastage} m³</span>
            </div>
            <div class="result-item">
                <span class="result-label">Cement Bags (50kg):</span>
                <span class="result-value">${results.cement_bags} bags</span>
            </div>
            <div class="result-item">
                <span class="result-label">Sand Required:</span>
                <span class="result-value">${results.sand_cubic_meters} m³</span>
            </div>
            <div class="result-item">
                <span class="result-label">Aggregate Required:</span>
                <span class="result-value">${results.aggregate_cubic_meters} m³</span>
            </div>
        `;
        document.querySelector('#concreteResults .results-card').innerHTML = html;
        document.getElementById('concreteResults').style.display = 'block';
    }

    function showError(message) {
        alert(message); // You can replace this with a more elegant notification system
    }

    @auth
    // Project saving functions
    window.showSaveProjectModal = function() {
        if (Object.keys(currentCalculations).length === 0) {
            alert('Please perform at least one calculation before saving.');
            return;
        }
        new bootstrap.Modal(document.getElementById('saveProjectModal')).show();
    };

    window.saveCurrentProject = function() {
        const form = document.getElementById('saveProjectForm');
        const formData = new FormData(form);
        
        const projectData = {
            project_name: formData.get('project_name'),
            total_estimated_cost: formData.get('total_estimated_cost'),
            notes: formData.get('notes'),
            calculations: currentCalculations
        };

        fetch('{{ route("calculator.save-project") }}', {
            method: 'POST',
            body: JSON.stringify(projectData),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('saveProjectModal')).hide();
                form.reset();
                loadSavedProjects();
                alert('Project saved successfully!');
            } else {
                alert('Error saving project');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving');
        });
    };

    function loadSavedProjects() {
        fetch('{{ route("calculator.saved-projects") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            displaySavedProjects(data.projects);
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('savedProjectsList').innerHTML = '<p class="text-danger">Error loading saved projects</p>';
        });
    }

    function displaySavedProjects(projects) {
        const container = document.getElementById('savedProjectsList');
        
        if (projects.length === 0) {
            container.innerHTML = `
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Saved Projects</h5>
                    <p class="text-muted">Save your calculations to access them later</p>
                </div>
            `;
            return;
        }

        let html = '';
        projects.forEach(project => {
            const calculationTypes = Object.keys(project.calculations);
            const calculationSummary = calculationTypes.map(type => 
                `<span class="badge bg-primary me-1">${type.charAt(0).toUpperCase() + type.slice(1)}</span>`
            ).join('');

            html += `
                <div class="saved-project-card">
                    <div class="saved-project-header">
                        <div>
                            <h6 class="mb-1">${project.project_name}</h6>
                            <small class="text-muted">Saved on ${new Date(project.created_at).toLocaleDateString()}</small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-primary me-2" onclick="loadProject(${project.id})">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteProject(${project.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="project-calculations">
                        <small class="text-muted">Calculations: </small>
                        ${calculationSummary}
                    </div>
                    ${project.total_estimated_cost ? `<div class="mt-2"><strong>Estimated Cost: ${project.total_estimated_cost}</strong></div>` : ''}
                    ${project.notes ? `<div class="mt-2"><small class="text-muted">${project.notes}</small></div>` : ''}
                </div>
            `;
        });

        container.innerHTML = html;
    }

    window.deleteProject = function(projectId) {
        if (!confirm('Are you sure you want to delete this project?')) {
            return;
        }

        fetch(`/customer/calculator/project/${projectId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadSavedProjects();
                alert('Project deleted successfully');
            } else {
                alert('Error deleting project');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting');
        });
    };

    window.loadProject = function(projectId) {
        // This function can be expanded to load and display project details
        alert('Project details view can be implemented here');
    };
    @endauth
});
</script>
@endsection
@extends('layout.landing')

@section('content')
<div id="banner-content" class="row clearfix">
    <div class="col-38">
        <div class="section-heading">
            <h1>Lyric Builder</h1>
            <h2 id="step-title">Entrance</h2>
            
            <div id="wizard-container" style="max-width: 600px; margin: 0 auto;">
                <!-- Progress -->
                <div class="progress-indicator" style="margin-bottom: 20px; font-size: 1.2em;">
                    Step <span id="current-step-num">1</span> of <span id="total-steps">10</span>
                </div>

                <!-- Search Section -->
                <div id="search-section">
                    <div style="position: relative;">
                        <input type="text" id="song-search" placeholder="Search for a song..." style="width: 100%; padding: 15px; margin-bottom: 10px; color: #333; border-radius: 5px; border: none;">
                        <div id="search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: white; color: #333; max-height: 200px; overflow-y: auto; display: none; border-radius: 0 0 5px 5px; z-index: 1000; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"></div>
                    </div>
                    
                    <div id="selected-song-display" style="margin-top: 15px; padding: 10px; background: rgba(255,255,255,0.1); border-radius: 5px; display: none;">
                        <span style="font-weight: bold;">Selected:</span> <span id="selected-song-title"></span>
                        <a href="javascript:void(0)" onclick="clearSelection()" style="color: #ff6b6b; margin-left: 10px; text-decoration: none;">[Remove]</a>
                    </div>
                </div>

                <!-- Final Step Message (Hidden initially) -->
                <div id="final-step-message" style="display: none; text-align: center; margin-bottom: 20px;">
                    <h3 style="color: white;">Collection Complete!</h3>
                    <p>Your mass selection is ready. You can preview the list or download the file below.</p>
                </div>

                <!-- Controls -->
                <div class="controls" style="margin-top: 30px;">
                    <button id="btn-prev" onclick="prevStep()" class="button" style="display: none; margin-right: 10px; background: transparent; border: 2px solid white;">Previous</button>
                    <button id="btn-next" onclick="nextStep()" class="button">Next Step</button>
                    <button id="btn-preview-popup" onclick="openPreviewModal()" class="button" style="display: none; margin-right: 10px; background: #17a2b8; border-color: #17a2b8;">Preview</button>
                    <button id="btn-download" onclick="downloadCollection()" class="button" style="display: none;">Download .txt</button>
                </div>
                
                <!-- Hidden form for download -->
                <form id="download-form" action="{{ route('lyric-builder.download') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="entrance" id="input-entrance">
                    <input type="hidden" name="kyrie" id="input-kyrie">
                    <input type="hidden" name="gloria" id="input-gloria">
                    <input type="hidden" name="credo" id="input-credo">
                    <input type="hidden" name="offertory" id="input-offertory">
                    <input type="hidden" name="consecration" id="input-consecration">
                    <input type="hidden" name="sanctus" id="input-sanctus">
                    <input type="hidden" name="agnus_dei" id="input-agnus_dei">
                    <input type="hidden" name="communion" id="input-communion">
                    <input type="hidden" name="dismissal" id="input-dismissal">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="preview-modal" class="modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.8);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 600px; color: #333; border-radius: 8px; position: relative;">
        <span class="close" onclick="closePreviewModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h3 style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 15px; color: #333;">Collection Preview</h3>
        <div id="preview-list" style="max-height: 60vh; overflow-y: auto;"></div>
        <div style="margin-top: 20px; text-align: right;">
            <button onclick="closePreviewModal()" class="button" style="background: #6c757d; border-color: #6c757d; padding: 5px 15px; font-size: 14px;">Close</button>
        </div>
    </div>
</div>

<script>
    const steps = [
        { key: 'entrance', label: 'Entrance', multi: true },
        { key: 'kyrie', label: 'Kyrie' },
        { key: 'gloria', label: 'Gloria' },
        { key: 'credo', label: 'Credo' },
        { key: 'offertory', label: 'Offertory', multi: true },
        { key: 'consecration', label: 'Consecration', multi: true },
        { key: 'sanctus', label: 'Sanctus' },
        { key: 'agnus_dei', label: 'Agnus Dei' },
        { key: 'communion', label: 'Communion', multi: true },
        { key: 'dismissal', label: 'Dismissal' }
    ];

    let currentStep = 0;
    const selections = {}; // { key: { id: 1, title: 'Song' } } or { key: [ ... ] }

    // Initialize selections
    steps.forEach(step => selections[step.key] = step.multi ? [] : null);

    const searchInput = document.getElementById('song-search');
    const resultsContainer = document.getElementById('search-results');
    const stepTitle = document.getElementById('step-title');
    const currentStepNum = document.getElementById('current-step-num');
    const totalSteps = document.getElementById('total-steps');
    const selectedDisplay = document.getElementById('selected-song-display');
    const selectedTitle = document.getElementById('selected-song-title'); // Kept for single mode structure, but we might replace innerHTML
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const btnPreviewPopup = document.getElementById('btn-preview-popup');
    const btnDownload = document.getElementById('btn-download');
    const searchSection = document.getElementById('search-section');
    const finalStepMessage = document.getElementById('final-step-message');
    const previewList = document.getElementById('preview-list');
    const previewModal = document.getElementById('preview-modal');

    totalSteps.textContent = steps.length;

    // Search Logic
    let debounceTimer;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const query = this.value;
        if (query.length < 2) {
            resultsContainer.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`{{ route('api.songs.search') }}?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(song => {
                            const div = document.createElement('div');
                            div.style.padding = '10px';
                            div.style.cursor = 'pointer';
                            div.style.borderBottom = '1px solid #eee';
                            div.onmouseover = () => div.style.background = '#f0f0f0';
                            div.onmouseout = () => div.style.background = 'white';
                            div.textContent = `${song.title} (${song.author || 'Unknown'})`;
                            div.onclick = () => selectSong(song);
                            resultsContainer.appendChild(div);
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.style.display = 'none';
                    }
                });
        }, 300);
    });

    function selectSong(song) {
        const step = steps[currentStep];
        if (step.multi) {
            // Avoid duplicates
            if (!selections[step.key].some(s => s.id === song.id)) {
                selections[step.key].push(song);
            }
        } else {
            selections[step.key] = song;
        }
        renderStep();
        resultsContainer.style.display = 'none';
        searchInput.value = '';
    }

    function clearSelection(index = null) {
        const step = steps[currentStep];
        if (step.multi) {
            if (index !== null) {
                selections[step.key].splice(index, 1);
            }
        } else {
            selections[step.key] = null;
        }
        renderStep();
    }

    function renderStep() {
        if (currentStep < steps.length) {
            // Wizard Mode
            searchSection.style.display = 'block';
            finalStepMessage.style.display = 'none';
            btnNext.style.display = 'inline-block';
            btnPreviewPopup.style.display = 'none';
            btnDownload.style.display = 'none';
            
            const step = steps[currentStep];
            stepTitle.textContent = step.label + (step.multi ? ' (Multiple Allowed)' : '');
            currentStepNum.textContent = currentStep + 1;

            const selection = selections[step.key];
            
            selectedDisplay.innerHTML = ''; // Clear previous content

            if (step.multi) {
                if (selection && selection.length > 0) {
                    selectedDisplay.style.display = 'block';
                    selection.forEach((song, idx) => {
                        const div = document.createElement('div');
                        div.style.marginBottom = '5px';
                        div.innerHTML = `<span style="font-weight: bold;">Selected:</span> ${song.title} <a href="javascript:void(0)" onclick="clearSelection(${idx})" style="color: #ff6b6b; margin-left: 10px; text-decoration: none;">[Remove]</a>`;
                        selectedDisplay.appendChild(div);
                    });
                    btnNext.textContent = 'Next Step';
                } else {
                    selectedDisplay.style.display = 'none';
                    btnNext.textContent = 'Skip / Next';
                }
            } else {
                if (selection) {
                    selectedDisplay.style.display = 'block';
                    selectedDisplay.innerHTML = `<span style="font-weight: bold;">Selected:</span> ${selection.title} <a href="javascript:void(0)" onclick="clearSelection()" style="color: #ff6b6b; margin-left: 10px; text-decoration: none;">[Remove]</a>`;
                    btnNext.textContent = 'Next Step';
                } else {
                    selectedDisplay.style.display = 'none';
                    btnNext.textContent = 'Skip / Next';
                }
            }
        } else {
            // Preview/End Mode
            searchSection.style.display = 'none';
            finalStepMessage.style.display = 'block';
            stepTitle.textContent = 'Ready to Download';
            btnNext.style.display = 'none';
            btnPreviewPopup.style.display = 'inline-block';
            btnDownload.style.display = 'inline-block';
        }

        btnPrev.style.display = currentStep > 0 ? 'inline-block' : 'none';
    }

    function openPreviewModal() {
        renderPreview();
        previewModal.style.display = 'block';
    }

    function closePreviewModal() {
        previewModal.style.display = 'none';
    }

    // Close modal if clicked outside of content
    window.onclick = function(event) {
        if (event.target == previewModal) {
            closePreviewModal();
        }
    }

    function renderPreview() {
        previewList.innerHTML = '';
        steps.forEach(step => {
            const div = document.createElement('div');
            div.style.marginBottom = '10px';
            const selection = selections[step.key];
            
            let titleHtml = '';
            if (step.multi) {
                if (selection && selection.length > 0) {
                    titleHtml = '<ul>' + selection.map(s => `<li>${s.title}</li>`).join('') + '</ul>';
                } else {
                    titleHtml = '<span style="color: #aaa; font-style: italic;">Not Found / Skipped</span>';
                }
            } else {
                titleHtml = selection ? selection.title : '<span style="color: #aaa; font-style: italic;">Not Found / Skipped</span>';
            }

            div.innerHTML = `<strong>${step.label}:</strong> ${titleHtml}`;
            previewList.appendChild(div);
        });
    }

    function nextStep() {
        if (currentStep < steps.length) {
            currentStep++;
            renderStep();
        }
    }

    function prevStep() {
        if (currentStep > 0) {
            currentStep--;
            renderStep();
        }
    }

    function downloadCollection() {
        // Populate form
        steps.forEach(step => {
            const input = document.getElementById(`input-${step.key}`);
            const selection = selections[step.key];
            if (step.multi) {
                // Send comma separated list of IDs
                input.value = selection ? selection.map(s => s.id).join(',') : '';
            } else {
                input.value = selection ? selection.id : '';
            }
        });
        document.getElementById('download-form').submit();
    }

    // Initial Render
    renderStep();
</script>
@endsection

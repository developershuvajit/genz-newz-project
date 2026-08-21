/**
 * GENZNEWZ — Interactive ePaper Viewer Engine
 * AJAX Page Switching • Smooth Canvas Zoom/Pan • Keyboard Navigation
 */

class EPaperViewer {
    constructor(options = {}) {
        this.editionSlug = options.editionSlug || '';
        this.currentPage = parseInt(options.currentPage || 1, 10);
        this.totalPages = parseInt(options.totalPages || 1, 10);
        
        // DOM Elements
        this.pageImg = document.getElementById('epaper-current-image');
        this.canvasWrapper = document.getElementById('epaper-canvas-wrapper');
        this.pageSelect = document.getElementById('page-select-dropdown');
        this.zoomValBadge = document.getElementById('zoom-value-badge');
        this.loadingOverlay = document.getElementById('epaper-loading-spinner');
        this.pageTitleEl = document.getElementById('epaper-page-title-label');

        // State
        this.zoom = 1;
        this.minZoom = 0.5;
        this.maxZoom = 2.8;
        this.isPanning = false;
        this.startX = 0;
        this.startY = 0;
        this.scrollLeft = 0;
        this.scrollTop = 0;

        this.init();
    }

    init() {
        if (!this.pageImg || !this.canvasWrapper) return;

        this.bindEvents();
        this.setupKeyboardShortcuts();
        this.setupPanDrag();
        this.updateUI();
    }

    bindEvents() {
        // Zoom buttons
        document.getElementById('btn-zoom-in')?.addEventListener('click', () => this.setZoom(this.zoom + 0.25));
        document.getElementById('btn-zoom-out')?.addEventListener('click', () => this.setZoom(this.zoom - 0.25));
        document.getElementById('btn-zoom-reset')?.addEventListener('click', () => this.setZoom(1));
        document.getElementById('btn-fit-width')?.addEventListener('click', () => this.fitWidth());

        // Page navigation buttons
        document.querySelectorAll('.btn-page-prev').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.prevPage();
            });
        });

        document.querySelectorAll('.btn-page-next').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.nextPage();
            });
        });

        // Dropdown selection
        if (this.pageSelect) {
            this.pageSelect.addEventListener('change', (e) => {
                this.goToPage(parseInt(e.target.value, 10));
            });
        }

        // Thumbnails sidebar clicks
        document.querySelectorAll('.thumb-item').forEach(item => {
            item.addEventListener('click', (e) => {
                const pageNum = parseInt(item.getAttribute('data-page'), 10);
                if (pageNum) {
                    this.goToPage(pageNum);
                }
            });
        });

        // Fullscreen toggle
        document.getElementById('btn-fullscreen')?.addEventListener('click', () => this.toggleFullscreen());

        // Double click to zoom toggle
        this.pageImg.addEventListener('dblclick', (e) => {
            if (this.zoom > 1.2) {
                this.setZoom(1);
            } else {
                this.setZoom(1.8);
            }
        });

        // Handle browser Back/Forward buttons via popstate
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.page) {
                this.goToPage(e.state.page, false);
            }
        });
    }

    setupKeyboardShortcuts() {
        window.addEventListener('keydown', (e) => {
            // Avoid triggering shortcuts inside input or select
            if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) return;

            switch (e.key) {
                case 'ArrowLeft':
                case 'a':
                case 'A':
                    this.prevPage();
                    break;
                case 'ArrowRight':
                case 'd':
                case 'D':
                    this.nextPage();
                    break;
                case '+':
                case '=':
                    this.setZoom(this.zoom + 0.25);
                    break;
                case '-':
                case '_':
                    this.setZoom(this.zoom - 0.25);
                    break;
                case '0':
                    this.setZoom(1);
                    break;
                case 'f':
                case 'F':
                    this.toggleFullscreen();
                    break;
            }
        });
    }

    setupPanDrag() {
        const wrapper = this.canvasWrapper;

        wrapper.addEventListener('mousedown', (e) => {
            if (this.zoom <= 1) return;
            this.isPanning = true;
            wrapper.classList.add('is-panning');
            this.startX = e.pageX - wrapper.offsetLeft;
            this.startY = e.pageY - wrapper.offsetTop;
            this.scrollLeft = wrapper.scrollLeft;
            this.scrollTop = wrapper.scrollTop;
        });

        window.addEventListener('mouseup', () => {
            this.isPanning = false;
            wrapper.classList.remove('is-panning');
        });

        wrapper.addEventListener('mousemove', (e) => {
            if (!this.isPanning) return;
            e.preventDefault();
            const x = e.pageX - wrapper.offsetLeft;
            const y = e.pageY - wrapper.offsetTop;
            const walkX = (x - this.startX) * 1.5;
            const walkY = (y - this.startY) * 1.5;
            wrapper.scrollLeft = this.scrollLeft - walkX;
            wrapper.scrollTop = this.scrollTop - walkY;
        });
    }

    setZoom(val) {
        this.zoom = Math.min(this.maxZoom, Math.max(this.minZoom, val));
        this.pageImg.style.transform = `scale(${this.zoom})`;
        if (this.zoomValBadge) {
            this.zoomValBadge.textContent = `${Math.round(this.zoom * 100)}%`;
        }
    }

    fitWidth() {
        const wrapperWidth = this.canvasWrapper.clientWidth - 40;
        const imgNaturalWidth = this.pageImg.naturalWidth || 800;
        const calculatedZoom = wrapperWidth / imgNaturalWidth;
        this.setZoom(calculatedZoom);
    }

    toggleFullscreen() {
        const target = document.getElementById('epaper-stage-view') || document.documentElement;
        if (!document.fullscreenElement) {
            target.requestFullscreen().catch(err => console.log(err));
        } else {
            document.exitFullscreen();
        }
    }

    prevPage() {
        if (this.currentPage > 1) {
            this.goToPage(this.currentPage - 1);
        }
    }

    nextPage() {
        if (this.currentPage < this.totalPages) {
            this.goToPage(this.currentPage + 1);
        }
    }

    async goToPage(pageNum, pushState = true) {
        if (pageNum < 1 || pageNum > this.totalPages || pageNum === this.currentPage) return;

        this.showLoading(true);

        try {
            const url = `/edition/${this.editionSlug}/page/${pageNum}?ajax=1`;
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (data && data.success) {
                this.currentPage = pageNum;
                this.pageImg.src = data.page_image;
                
                if (this.pageTitleEl && data.page_title) {
                    this.pageTitleEl.textContent = data.page_title;
                }

                if (pushState) {
                    history.pushState({ page: pageNum }, '', `/edition/${this.editionSlug}/page/${pageNum}`);
                }

                this.setZoom(1);
                this.updateUI();
            }
        } catch (error) {
            // Fallback to normal page load
            window.location.href = `/edition/${this.editionSlug}/page/${pageNum}`;
        } finally {
            this.showLoading(false);
        }
    }

    updateUI() {
        // Update select dropdown
        if (this.pageSelect) {
            this.pageSelect.value = this.currentPage;
        }

        // Update thumbnails active class
        document.querySelectorAll('.thumb-item').forEach(item => {
            const itemPage = parseInt(item.getAttribute('data-page'), 10);
            if (itemPage === this.currentPage) {
                item.classList.add('active');
                item.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                item.classList.remove('active');
            }
        });

        // Disable/enable arrows
        document.querySelectorAll('.btn-page-prev').forEach(btn => {
            btn.style.opacity = (this.currentPage <= 1) ? '0.4' : '1';
            btn.style.pointerEvents = (this.currentPage <= 1) ? 'none' : 'auto';
        });

        document.querySelectorAll('.btn-page-next').forEach(btn => {
            btn.style.opacity = (this.currentPage >= this.totalPages) ? '0.4' : '1';
            btn.style.pointerEvents = (this.currentPage >= this.totalPages) ? 'none' : 'auto';
        });
    }

    showLoading(show) {
        if (this.loadingOverlay) {
            this.loadingOverlay.style.display = show ? 'flex' : 'none';
        }
    }
}

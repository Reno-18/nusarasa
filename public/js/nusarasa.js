// NusaRasa - AJAX Functions

$(document).ready(function() {
    // Category button click
    $(document).on('click', '.category-btn', function() {
        $('.category-btn').removeClass('bg-nusarasa-dark text-white');
        $(this).addClass('bg-nusarasa-dark text-white');
        
        const category = $(this).data('category');
        $('#category-filter').val(category).trigger('change');
    });

    let currentPage = 1;

    // Get current filter values
    function getFilters() {
        return {
            search: $('#search-input').val(),
            category: $('#category-filter').val(),
            sort: $('#sort-filter').val() || 'all',
            page: currentPage
        };
    }

    // Initial Load
    function loadRecipes(append = false) {
        if (!append) {
            currentPage = 1;
            $('#recipe-grid').html(`
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-nusarasa-dark opacity-50">
                    <div class="w-16 h-16 border-4 border-nusarasa-dark border-t-nusarasa-pink rounded-full animate-spin mb-4"></div>
                    <p class="font-black uppercase tracking-widest text-lg">Mencampur Resep...</p>
                </div>
            `);
            $('#load-more-container').addClass('hidden');
        } else {
            const btn = $('#load-more-btn');
            btn.html('<div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Memuat...');
        }

        const filters = getFilters();
        $.ajax({
            url: '/api/recipes',
            method: 'GET',
            data: filters,
            success: function(response) {
                renderRecipeGrid(response.data.recipes, append);
                
                // Handle Load More button visibility
                const pagination = response.data.pagination;
                if (pagination && pagination.current_page < pagination.last_page) {
                    $('#load-more-container').removeClass('hidden');
                    $('#load-more-btn').html(`
                        Muat Lebih Banyak
                        <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    `);
                } else {
                    $('#load-more-container').addClass('hidden');
                }
            },
            error: function(xhr) {
                console.error('Fetch error:', xhr);
            }
        });
    }

    if ($('#recipe-grid').length > 0) {
        loadRecipes();
    }

    // Load More click
    $(document).on('click', '#load-more-btn', function() {
        currentPage++;
        loadRecipes(true);
    });

    // AJAX 1 — Live Search
    let searchTimer;
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimer);
        currentPage = 1;
        
        searchTimer = setTimeout(function() {
            loadRecipes(false);
        }, 400);
    });

    // Category filter change
    $('#category-filter').on('change', function() {
        currentPage = 1;
        loadRecipes(false);
    });

    // Sort filter change
    $('#sort-filter').on('change', function() {
        currentPage = 1;
        loadRecipes(false);
    });

    function renderRecipeGrid(recipes, append = false) {
        let html = '';
        const colors = ['bg-nusarasa-pink', 'bg-nusarasa-purple', 'bg-nusarasa-yellow', 'bg-blue-100', 'bg-green-100'];
        
        if (recipes.length === 0) {
            html = '<div class="col-span-3 text-center py-20"><p class="text-nusarasa-dark font-black text-3xl uppercase opacity-20">No mood found</p></div>';
        } else {
            recipes.forEach(function(recipe, index) {
                const imageUrl = recipe.image_url || 'https://via.placeholder.com/400x300?text=No+Image';
                const category = recipe.category || 'Uncategorized';
                const origin = recipe.origin || 'Global';
                const source = recipe.source || 'local';
                const sourceColor = source === 'api' ? 'bg-blue-500' : 'bg-green-500';
                const sourceText = source === 'api' ? 'API' : 'Lokal';
                const detailUrl = source === 'api' ? `/recipes/api/${recipe.id}` : `/recipes/${recipe.id}`;
                const bgColor = colors[index % colors.length];
                const rating = recipe.ratings_avg_score ? parseFloat(recipe.ratings_avg_score).toFixed(1) : '0.0';
                
                const recipeIdStr = (recipe.id || '').toString();
                const isSaved = source === 'api' 
                    ? (window.savedMealApiIds || []).map(String).includes(recipeIdStr)
                    : (window.savedRecipeIds || []).map(Number).includes(parseInt(recipe.id));

                html += `
                    <div class="group relative bg-white border-2 border-nusarasa-dark rounded-4xl p-6 hover:-translate-y-2 transition duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="${(index % 3) * 100}">
                        <div class="w-full h-72 rounded-3xl overflow-hidden mb-6 ${bgColor} flex items-center justify-center relative">
                            <img src="${imageUrl}" alt="${recipe.title}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                            
                            <button class="save-btn absolute top-4 right-4 w-12 h-12 ${isSaved ? 'bg-nusarasa-dark text-white' : 'bg-white text-nusarasa-dark'} border-2 border-nusarasa-dark rounded-full flex items-center justify-center text-xl hover:bg-nusarasa-dark hover:text-white transition" 
                                    data-recipe-id="${source === 'local' ? recipe.id : ''}" 
                                    data-meal-api-id="${source === 'api' ? recipe.id : ''}"
                                    data-title="${recipe.title}"
                                    data-image="${imageUrl}"
                                    ${isSaved ? 'disabled' : ''}>
                                ${isSaved ? '✓' : '♥'}
                            </button>
                        </div>

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-black uppercase tracking-tighter text-nusarasa-dark opacity-60">
                                ${category} • ${origin}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">${sourceText}</span>
                                <span class="w-2 h-2 rounded-full ${sourceColor}"></span>
                            </div>
                        </div>

                        <h3 class="text-2xl font-black font-display text-nusarasa-dark mb-6 leading-tight flex-1 uppercase tracking-tighter">${recipe.title}</h3>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t-2 border-nusarasa-dark/5">
                            <div class="flex items-center gap-2">
                                <span class="text-yellow-400 font-bold">★</span>
                                <span class="font-black text-sm">${rating}</span>
                            </div>
                            <a href="${detailUrl}" class="px-6 py-3 bg-nusarasa-dark text-white rounded-pill font-black text-[10px] uppercase tracking-widest hover:bg-opacity-80 transition flex items-center gap-2">
                                Detail
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                `;
            });
        }
        
        if (append) {
            $('#recipe-grid').append(html);
        } else {
            $('#recipe-grid').html(html);
        }
    }

    // AJAX 2 — Save / Unsave Recipe
    $(document).on('click', '.save-btn', function() {
        const recipeId = $(this).data('recipe-id');
        const mealApiId = $(this).data('meal-api-id');
        const title = $(this).data('title');
        const image = $(this).data('image');
        const btn = $(this);
        
        $.ajax({
            url: '/api/saved-recipes',
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { 
                recipe_id: recipeId || null,
                meal_api_id: mealApiId || null,
                meal_api_title: title || null,
                meal_api_image: image || null
            },
            success: function(response) {
                btn.html('✓').addClass('bg-nusarasa-dark text-white').prop('disabled', true);
                
                if (mealApiId) {
                    if (!window.savedMealApiIds) window.savedMealApiIds = [];
                    window.savedMealApiIds.push(mealApiId.toString());
                } else if (recipeId) {
                    if (!window.savedRecipeIds) window.savedRecipeIds = [];
                    window.savedRecipeIds.push(parseInt(recipeId));
                }

                showToast('Resep berhasil disimpan! Mengalihkan...', 'success');
                setTimeout(() => window.location.href = '/saved', 1000);
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showToast('Silakan login terlebih dahulu', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    showToast('Gagal menyimpan resep', 'error');
                }
            }
        });
    });


});

// Toast notification function
function showToast(message, type) {
    const color = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const toast = $(`
        <div class="fixed bottom-4 right-4 ${color} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in">
            ${message}
        </div>
    `);
    
    $('body').append(toast);
    
    setTimeout(() => {
        toast.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}

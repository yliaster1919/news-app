<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <title>News App</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Styles -->

</head>

<body class="antialiased bg-sky-50">
    <div class="flex flex-col items-center justify-center border-[1px] border-gray-200 p-16 shadow-md mx-16 mt-8 bg-white">
        <form action='/' method="GET" class="flex items-center justify-center w-full">
            <input id='searchInput' required name="search-input" class="w-full border-[2px] rounded-md border-slate-400 p-2 outline-sky-500" placeholder="Type your search here ..">
            </input>
            <div class="flex items-center justify-center px-4 w-[300px]">
                <p class="text-xs text-gray-500 p-2">Order by:</p>
                <select id="orderBy" name="order-by" class="border-[2px] rounded-md border-slate-400 p-2 outline-sky-500 accent-sky-500 group-hover:bg-blue-500 group-hover:text-white">
                    <option value="newest">newest</option>
                    <option value="oldest">oldest</option>
                    <option value="relevance">relevance</option>
                </select>
            </div>
            <button type="submit" class="py-2 px-4 bg-sky-400 hover:bg-sky-800 text-white rounded-md w-32 mx-4">Search</button>
        </form>
    </div>
    <div class="flex flex-col justify-start shadow-md mx-16 my-8  border-[1px] border-gray-200 h-auto bg-white" id='news'>
        <!-- news section-->
        <div class="flex justify-between items-center">
            <!-- pagination -->
            @if(!count($news) == 0)
            <div class="text-slate-400 text-medium p-4">
                @if(!empty($queryParams))
                Search results under "{{ $queryParams['search-input'] ?? '' }}" by {{ $queryParams['order-by'] ?? '' }}
                @endif
            </div>

            <div class="flex justify-end items center p-4" class="pagination" id="pagination">
                <input id="pagination-total-pages" value="{{$pagination['pages']}}" hidden />
                <input id="pagination-current-page" value="{{$pagination['currentPage']}}" hidden />
                @php
                    $last = $pagination['pages']
                @endphp
                <!-- <button id='firstpage' class="mx-2" onclick="goToPage('1')">First</button>  -->
                <button id="prevBtn" class="hover:text-sky-600" disabled="{{$pagination['currentPage'] == $pagination['pages']}}">Previous</button>
                <span id="pageNumbers"></span>
                <button id="nextBtn" class="hover:text-sky-600" disabled="{{$pagination['currentPage'] == 1}}">Next</button>
                <!-- <button id='lastpage' class="mx-2" onclick="goToPage('{{$last}}')">Last</button>  -->
            </div>
            @endif
        </div>
        @if(count($news) == 0)
        <div class="text-center py-4">No results found</div>
        @else
        @foreach($news as $index => $article)
        @php
        $formattedDate = \Carbon\Carbon::parse($article['webPublicationDate'])->format('m/d/Y');
        $id = $article['id'];
        $type = $article['type'];
        $webTitle = $article['webTitle'];
        $sectionId = $article['sectionId'];
        $sectionName = $article['sectionName'];
        $apiUrl = $article['apiUrl'];
        $webUrl = $article['webUrl'];
        @endphp
        <div class="flex w-full justify-between my-4">
            <div class="px-4">
                <div class="flex items-center">
                    <p class="text-xs text-gray-400">Date published: </P>
                    <p class="text-emerald-500 text-sm px-2"> {{$formattedDate}}</P>
                </div>
                <h2 class="text-lg font-semibold text-slate-800">{{$article['webTitle']}}</h2>
                <a href="{{$article['webUrl']}}" target="_blank" class="text-blue-400 hover:underline">{{$article['webUrl']}}</a>
            </div>
            <div class="flex justify-between items-center">
                @if($pinnedNews->contains('newsId', $id))
                <button id="pinButton-{{ $index }}" name="button-{{$id}}" class='py-[2px] px-4 bg-red-400 hover:bg-red-800 text-white rounded-sm m-2 mx-4' onclick="unpinArticle('{{ $id }}' , '{{$index}}')">Unpin</button>
                @else
                <button id="pinButton-{{ $index }}" name="button-{{$id}}" class='py-[2px] px-4 bg-sky-400 hover:bg-sky-800 text-white rounded-sm m-2 mx-4' onclick="pinArticle(
                    '{{ $id }}',
                    '{{ $type }}',
                    '{{ $webTitle }}',
                    '{{ $sectionId }}',
                    '{{ $sectionName }}',
                    '{{ $apiUrl }}',
                    '{{ $webUrl }}',
                    '{{ $formattedDate }}',
                    '{{ $index}}'
                )">Pin
                </button>
                @endif
            </div>
        </div>
        @endforeach

        @endif


    </div>
    <div class="flex flex-col justify-start shadow-md mx-16 my-8  border-[1px] border-gray-200 h-auto  bg-white" id='pinned-news'>
        <!-- pinned news section -->
        <h1 class="p-4 text-lg text-slate-800 font-bold">Pinned News</h1>
        @if(empty($pinnedNews) || count($pinnedNews) === 0)
        <div class="text-center py-4" id="no-pinned-articles">No pinned articles<div>
                @else
                @foreach($pinnedNews as $index => $pinnedArticle)
                @php
                $formattedDate = \Carbon\Carbon::parse($pinnedArticle['webPublicationDate'])->format('m/d/Y');
                $id = $pinnedArticle['newsId'];
                $type = $pinnedArticle['type'];
                $webTitle = $pinnedArticle['webTitle'];
                $sectionId = $pinnedArticle['sectionId'];
                $sectionName = $pinnedArticle['sectionName'];
                $apiUrl = $pinnedArticle['apiUrl'];
                $webUrl = $pinnedArticle['webUrl'];
                @endphp
                <div class="flex w-full justify-between my-4" name="{{$pinnedArticle['newsId']}}">
                    <div class="px-4">
                        <div class="flex items-center">
                            <p class="text-xs text-gray-400">Date published: </P>
                            <p class="text-emerald-500 text-sm px-2"> {{$formattedDate}}</P>
                        </div>
                        <h2 class="text-lg font-semibold text-slate-800">{{$pinnedArticle['webTitle']}}</h2>
                        <a href="{{$pinnedArticle['webUrl']}}" target="_blank" class="text-blue-400 hover:underline">{{$pinnedArticle['webUrl']}}</a>
                    </div>
                    <div class="flex justify-between items-center">
                        <button id="pinnedNews-pinButton-{{ $index }}" class='py-[2px] px-4 bg-red-400 hover:bg-red-800 text-white rounded-sm m-2 mx-4' onclick="unpinArticle('{{ $id }}' , '{{$index}}')">Unpin</button>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
</body>
<!--script functions-->
@if(count($news) > 0)
<script>
    const paginationContainer = document.getElementById('pagination');
    const prevButton = document.getElementById('prevBtn');
    const nextButton = document.getElementById('nextBtn');
    const pageNumbersContainer = document.getElementById('pageNumbers');

    const totalPagesElement = document.getElementById('pagination-total-pages')
    const totalPages = parseInt(totalPagesElement.value, 10)
    let currentPage = parseInt(document.getElementById('pagination-current-page').value, 10)
    console.log('pagination: ', totalPages)

    function updatePagination() {
        // Update page numbers
        let pageNumbers = '';
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(startPage + 4, totalPages);

        if (currentPage > totalPages - 2) {
            startPage = totalPages - 4;
            startPage = Math.max(1, startPage);
        }

        for (let i = startPage; i <= endPage; i++) {
            pageNumbers += i == currentPage ? `<input value="${i}" class="text-sky-500 w-[25px] mx-2 text-center" onkeydown="goToPage(event.target.value)"/>` : `<button onclick="goToPage(${i})" class="px-4">${i}</button>`;
        }

        pageNumbersContainer.innerHTML = pageNumbers;

        // Enable/disable previous and next buttons
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = currentPage === totalPages;
    }

    function goToPage(page) {
        console.log('goto called: ', parseInt(page, 10))
        currentPage = parseInt(page, 10);
        const searchParams = new URLSearchParams(window.location.search);
        searchParams.set('page', currentPage);
        const newUrl = `/?${searchParams.toString()}`;
        window.location.href = newUrl;
    }

    // Initial update
    updatePagination();

    // Event listeners for previous and next buttons
    prevButton.addEventListener('click', function() {
        if (currentPage > 1) {
            goToPage(currentPage - 1);
        }
    });

    nextButton.addEventListener('click', function() {
        if (currentPage < totalPages) {
            goToPage(currentPage + 1);
        }
    });
</script>

<script>
    function updateButton(name, index, isPinned) {
        const button = document.querySelector(`[name="button-${name}"]`);
        button.innerHTML = isPinned ? 'Unpin' : 'Pin';
        var action = isPinned ? "unpinArticle('"+name+ "', '" + index + "')" : "pinArticle('"+name+ "', '{{ $type }}', '{{ $webTitle }}', '{{ $sectionId }}', '{{ $sectionName }}', '{{ $apiUrl }}', '{{ $webUrl }}', '{{ $formattedDate }}',  '" + index + "')";
        var css = isPinned ? 'py-[2px] px-4 bg-red-400 hover:bg-red-800 text-white rounded-sm m-2 mx-4' : 'py-[2px] px-4 bg-sky-400 hover:bg-sky-800 text-white rounded-sm m-2 mx-4';
        button.setAttribute("onclick", action);
        button.setAttribute("class", css)
    }

    function pinArticle(newsId, type, webTitle, sectionId, sectionName, apiUrl, webUrl, webPublicationDate, index) {
        const data = {
            newsId: newsId,
            type: type,
            webTitle: webTitle,
            sectionId: sectionId,
            sectionName: sectionName,
            apiUrl: apiUrl,
            webPublicationDate: webPublicationDate,
            webUrl: webUrl,
            index: index
        };

        fetch('/api/pin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
            }).then(response => response.json())
            .then(data => {
                console.log(data.message, index);
                addPinned(data.data, index)
                updateButton(data.data.newsId, index, true);
            })
            .catch(error => {
                console.error('Error pinning news:', error);
            });
    }

    function addPinned(pinnedArticle, index) {
        const noPinnedMessage = document.querySelector('#no-pinned-articles');
        if (noPinnedMessage) {
            console.log('no articles')
            noPinnedMessage.remove();
        }
        const formattedDate = pinnedArticle['webPublicationDate'];
        const pinnedArticleDiv = document.createElement('div');
        pinnedArticleDiv.className = 'flex w-full justify-between my-4';
        pinnedArticleDiv.id = `pinnedArticle-${index}`;
        pinnedArticleDiv.setAttribute('name', pinnedArticle['newsId']);
        pinnedArticleDiv.innerHTML = `
            <div class="px-4">
                <div class="flex items-center">
                    <p class="text-xs text-gray-400">Date published: </p>
                    <p class="text-emerald-500 text-sm px-2">${formattedDate}</p>
                </div>
                <h2 class="text-lg font-semibold text-slate-800">${pinnedArticle['webTitle']}</h2>
                <a href="${pinnedArticle['webUrl']}" target="_blank" class="text-blue-400 hover:underline">${pinnedArticle['webUrl']}</a>
            </div>
            <div class="flex justify-between items-center">
                <button id="pinnedNews-pinButton-${index}" class='py-[2px] px-4 bg-red-400 hover:bg-red-800 text-white rounded-sm m-2 mx-4' onclick="unpinArticle('${pinnedArticle['newsId']}', '${index}')">Unpin</button>
            </div>
        `;

        // Append the new pinned article to the pinned-news section
        const pinnedNewsSection = document.getElementById('pinned-news');
        pinnedNewsSection.appendChild(pinnedArticleDiv);
    }

    function removePinned(name) {
        const pinnedArticle = document.querySelector(`[name="${name}"]`);
        console.log('pinned article', pinnedArticle)
        pinnedArticle.remove();
        const pinnedNewsSection = document.getElementById('pinned-news');
        if (pinnedNewsSection.children.length === 1) {
            const noPinnedMessage = document.createElement('div');
            noPinnedMessage.className = 'text-center py-4';
            noPinnedMessage.id = 'no-pinned-articles'
            noPinnedMessage.textContent = 'No pinned articles';
            pinnedNewsSection.appendChild(noPinnedMessage);
        }
    }

    function unpinArticle(newsId, index) {
        const data = {
            newsId: newsId
        };
        fetch('/api/unpin', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data),
            }).then(response => response.json())
            .then(data => {
                console.log(data.message, index);
                removePinned(newsId);
                updateButton(newsId, index, false);
            })
            .catch(error => {
                console.error('Error unpinning news:', error);
            });
    }
    // function handleSearch(event) {
    //     console.log('handle search called');
    //     var searchInput = document.getElementById('searchInput').value;
    //     var orderBy = document.getElementById('orderBy').value;

    //     var query = {
    //         searchInput: searchInput,
    //         orderBy: orderBy
    //     }
    //     console.log('handle search called', query['searchInput'], query['orderBy']);
    //     fetchNewsData(query);
    // }

    // function fetchNewsData(query) {
    //     fetch('/?q=' + encodeURIComponent(query['searchInput']) + '&orderBy=' + encodeURIComponent(query['orderBy']))
    //         .then(response => response.json())
    //         .then(data => {
    //             document.getElementById('news').innerHTML = data;
    //         })
    //         .catch(error => console.error('Error fetching news data:', error));
    // }
</script>
@endif

</html>
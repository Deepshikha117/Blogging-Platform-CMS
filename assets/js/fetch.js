document.addEventListener("DOMContentLoaded", function () {

    /* ===== LIVE SEARCH ===== */
    const searchInput = document.getElementById("liveSearch");
    const resultBox = document.getElementById("liveResults");

    if (searchInput && resultBox) {
        searchInput.addEventListener("keyup", function () {
            let q = this.value.trim();

            if (q.length < 2) {
                resultBox.innerHTML = "";
                return;
            }

            fetch("search_ajax.php?q=" + encodeURIComponent(q))
                .then(res => res.json())
                .then(data => {
                    let html = "<ul>";
                    data.forEach(p => {
                        html += `<li><a href="post.php?id=${p.id}">${p.title}</a></li>`;
                    });
                    html += "</ul>";
                    resultBox.innerHTML = html;
                });
        });
    }

    /* ===== LOAD MORE ===== */
    let offset = 6;
    const loadBtn = document.getElementById("loadMore");

    if (loadBtn) {
        loadBtn.addEventListener("click", function () {
            fetch("load.php?offset=" + offset)
                .then(r => r.text())
                .then(data => {
                    if (data.trim() === "") {
                        this.innerText = "No more posts";
                        this.disabled = true;
                    } else {
                        document.getElementById("posts")
                            .insertAdjacentHTML("beforeend", data);
                        offset += 3;
                    }
                });
        });
    }

});

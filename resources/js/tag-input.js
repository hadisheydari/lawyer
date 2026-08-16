export function initTagInput(container) {
    if (container.dataset.tagInputReady) return;
    container.dataset.tagInputReady = "1";

    const input = container.querySelector(".tag-input-field");
    const hiddenContainer = container.querySelector(".tag-hidden-inputs");
    const chipsWrap = container.querySelector(".tag-chips");
    const fieldName = container.dataset.name;

    let tags = [];
    try {
        tags = JSON.parse(container.dataset.initial || "[]");
    } catch (e) {
        tags = [];
    }

    function render() {
        chipsWrap.innerHTML = "";
        hiddenContainer.innerHTML = "";
        tags.forEach((tag, idx) => {
            const chip = document.createElement("span");
            chip.className = "tag-chip";
            const text = document.createElement("span");
            text.className = "tag-chip-text";
            text.textContent = tag;
            const btn = document.createElement("button");
            btn.type = "button";
            btn.className = "tag-chip-remove";
            btn.dataset.idx = idx;
            btn.innerHTML = "&times;";
            chip.appendChild(text);
            chip.appendChild(btn);
            chipsWrap.appendChild(chip);

            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = `${fieldName}[]`;
            hidden.value = tag;
            hiddenContainer.appendChild(hidden);
        });
    }

    function addTag(value) {
        const v = value.trim().replace(/,+$/, "").trim();
        if (!v) return;
        if (tags.some((t) => t.toLowerCase() === v.toLowerCase())) {
            input.value = "";
            return;
        }
        tags.push(v);
        render();
        input.value = "";
    }

    chipsWrap.addEventListener("click", (e) => {
        const btn = e.target.closest(".tag-chip-remove");
        if (!btn) return;
        tags.splice(parseInt(btn.dataset.idx, 10), 1);
        render();
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter" || e.key === ",") {
            e.preventDefault();
            addTag(input.value);
        } else if (e.key === "Backspace" && input.value === "" && tags.length) {
            tags.pop();
            render();
        }
    });

    input.addEventListener("blur", () => {
        if (input.value.trim()) addTag(input.value);
    });

    render();
}

export function initAllTagInputs(root = document) {
    root.querySelectorAll(".tag-input-wrap").forEach(initTagInput);
}

export function copyValueToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text);
    } else if (window.clipboardData) {
        window.clipboardData.setData("Text", text);
    } else {
        const input = document.createElement("input");
        const [scrollTop, scrollLeft] = [
            document.documentElement.scrollTop,
            document.documentElement.scrollLeft,
        ];
        document.body.appendChild(input);
        input.value = text;
        input.focus();
        input.select();
        document.documentElement.scrollTop = scrollTop;
        document.documentElement.scrollLeft = scrollLeft;
        document.execCommand("copy");
        input.remove();
    }
}

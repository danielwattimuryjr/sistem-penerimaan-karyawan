document.addEventListener("DOMContentLoaded", () => {
  const themeOptions = document.body.classList.contains("dark")
    ? {
        skin: "oxide-dark",
        content_css: "dark",
      }
    : {
        skin: "oxide",
        content_css: "default",
      };

  tinymce.init({
    selector: "#test",
    plugins: "lists, link, image, media",
    toolbar:
      "h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link ",
    menubar: false,
    editable_root: false,
    ...themeOptions,
  });

  tinymce.init({
    selector: "#default",
    plugins: "lists, link, image, media",
    toolbar:
      "h1 h2 bold italic strikethrough blockquote bullist numlist backcolor | link ",
    menubar: false,
    ...themeOptions,
  });
});

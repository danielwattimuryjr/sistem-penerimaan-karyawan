const searchParams = new URLSearchParams(window.location.search);
const Swal2 = Swal.mixin({
  customClass: {
    input: "form-control",
  },
});

if (searchParams.has("type") && searchParams.has("message")) {
  const type = searchParams.get("type");
  const message = searchParams.get("message");
  Swal2.fire({
    icon: type,
    title: type === "error" ? "Oops.." : "Success",
    text: message,
  });
}

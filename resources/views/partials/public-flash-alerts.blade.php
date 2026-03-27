@php
    $publicFeedback = session('public_feedback');
    $wishlistFeedback = session('wishlist_feedback');
    $feedback = $publicFeedback ?: $wishlistFeedback;
@endphp

@if ($feedback)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var feedback = @json($feedback);

            if (window.Swal) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: feedback.icon || 'info',
                    title: feedback.title || '',
                    text: feedback.text || '',
                    showConfirmButton: false,
                    timer: 2400,
                    timerProgressBar: true,
                });

                return;
            }

            if (feedback.text) {
                window.alert(feedback.text);
            }
        });
    </script>
@endif

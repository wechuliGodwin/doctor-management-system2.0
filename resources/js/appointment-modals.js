$(document).ready(function () {
    // Function to show the suggested dates modal
    function showSuggestedDatesModal(errorMessage, suggestedDates, dateFieldId) {
        const modalElement = document.getElementById('suggestedDatesModal');
        if (!modalElement) {
            console.error('Suggested dates modal not found');
            alert('Error: Modal not found. Please contact support.');
            return;
        }

        const messageElement = $('#suggested-dates-message');
        const list = $('#suggested-dates-list');
        messageElement.text(errorMessage);
        list.empty();

        if (suggestedDates && suggestedDates.length) {
            suggestedDates.forEach(date => {
                const li = $('<li></li>')
                    .addClass('list-group-item')
                    .text(new Date(date).toLocaleDateString('en-US', {
                        month: 'numeric',
                        day: 'numeric',
                        year: 'numeric'
                    }))
                    .on('click', function () {
                        $(`#${dateFieldId}`).val(date);
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        modal.hide();
                    });
                list.append(li);
            });
            const suggestedDatesModal = new bootstrap.Modal(modalElement);
            suggestedDatesModal.show();
        } else {
            alert('No alternative dates available. Please try a different specialization or contact support.');
        }
    }

    // Handle form submissions for add, reschedule, and approve external
    $('form#appointmentForm, form#rescheduleForm, form[id^="approve-form-"]').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const formId = form.attr('id');
        let dateFieldId;

        if (formId === 'appointmentForm') {
            dateFieldId = 'appointment_date';
        } else if (formId === 'rescheduleForm') {
            dateFieldId = 'modal_appointment_date';
        } else if (formId.startsWith('approve-form-')) {
            const appointmentNumber = formId.replace('approve-form-', '');
            dateFieldId = `appointment_date_${appointmentNumber}`;
        }

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                window.location.reload();
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.error && xhr.responseJSON.suggested_dates) {
                    showSuggestedDatesModal(xhr.responseJSON.error, xhr.responseJSON.suggested_dates, dateFieldId);
                } else {
                    alert('An error occurred: ' + (xhr.responseJSON?.message || 'Unknown error'));
                }
            }
        });
    });

    // Handle specialization limit form submission
    $('#limitForm').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const date = $('#modal_date').val();
        const days = form.find('input[name="days_of_week[]"]:checked').map(function () {
            return this.value;
        }).get();
        const isClosed = $('#modal_is_closed').is(':checked');
        const selectedDay = new Date(date).toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();

        if (!isClosed && !days.includes('daily') && !days.includes(selectedDay)) {
            $.ajax({
                url: '{{ route("booking.specialization.suggested.dates") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    specialization_id: $('#modal_specialization_id').val(),
                    from_date: date
                },
                success: function (response) {
                    showSuggestedDatesModal(
                        `Cannot set limit for ${new Date(date).toLocaleDateString('en-US')}. Select a date when this specialization is available:`,
                        response.suggested_dates,
                        'modal_date'
                    );
                },
                error: function (xhr) {
                    alert('Failed to fetch suggested dates. Please try again or contact support.');
                }
            });
        } else {
            form.unbind('submit').submit();
        }
    });
});
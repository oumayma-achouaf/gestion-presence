import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {

    const shifts = document.querySelectorAll('.shift');

    shifts.forEach(el => {

        el.addEventListener('change', async function () {
            const url = this.dataset.url || this.closest('form')?.action;

            if (!url) {
                return;
            }

            // UI loading state
            this.style.border = "2px solid orange";

            try {

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        employee_id: this.dataset.emp,
                        date: this.dataset.date,
                        shift: this.value
                    })
                });

                // check if request failed
                if (!response.ok) {
                    throw new Error('Server error');
                }

                const contentType = response.headers.get('content-type') || '';
                const data = contentType.includes('application/json') ? await response.json() : null;

                console.log("Saved", data);

                // success UI
                this.style.border = "2px solid green";

            } catch (error) {

                console.log("Error", error);

                // error UI
                this.style.border = "2px solid red";
            }

        });

    });

});




document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.attendance').forEach(el => {

        el.addEventListener('change', function () {
            const url = this.dataset.url || this.closest('form')?.action;

            if (!url) {
                return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    user_id: this.dataset.user,
                    date: this.dataset.date,
                    status: this.value
                })
            })
            .then(r => {
                if (!r.ok) {
                    throw new Error('Server error');
                }
            })
            .then(() => {
                this.style.border = "2px solid green";
            })
            .catch(() => {
                this.style.border = "2px solid red";
            });

        });

    });

});

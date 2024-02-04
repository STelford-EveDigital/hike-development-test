<template>
    <div>
        <h1 class="text-4xl font-semibold uppercase tracking-wider text-white text-center">Book your MOT and Service</h1>
        
        <div class="mt-8 h-full mb-16 px-6 py-8 bg-white shadow rounded-md">
            <!-- Customer Data -->
            <div v-if="step === 1" class="flex flex-col">
                <div class="flex flex-col space-y-4">
                    <div class="flex flex-col space-y-1">
                        <label for="name" class="text-sm font-medium">Name <span class="text-red-500">*</span></label>
                        <input type="text" v-model="name" class="p-2 rounded-md">
                        <span v-if="errors.name" class="text-sm text-red-500" v-text="errors.name"></span>
                    </div>
    
                    <div class="flex flex-col space-y-1">
                        <label for="name" class="text-sm font-medium">Email Address <span class="text-red-500">*</span></label>
                        <input type="text" v-model="email" class="p-2 rounded-md">
                        <span v-if="errors.email" class="text-sm text-red-500" v-text="errors.email"></span>
                    </div>
    
                    <div class="flex flex-col space-y-1">
                        <label for="name" class="text-sm font-medium">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" v-model="phone" class="p-2 rounded-md">
                        <span v-if="errors.phone" class="text-sm text-red-500" v-text="errors.phone"></span>
                    </div>
                </div>

                <div class="flex mt-8">
                    <button type="button" v-on:click="validateStepOne" class="p-4 text-white font-semibold bg-sky-500 hover:bg-sky-600 rounded-md w-full transition duration-300">
                        Continue
                    </button>
                </div>
            </div>

            <!-- Car Make/Model -->
            <div v-if="step === 2">
                <div v-if="!showMakeModelManual">
                    <div class="flex flex-col space-y-1">
                        <label for="vehicleRegistration" class="text-sm font-medium">Vehicle Registration<span class="text-red-500">*</span></label>
                        <input type="text" v-model="vehicleRegistration" class="p-2 rounded-md">
                        <span v-if="errors.vehicleRegistration" class="text-sm text-red-500" v-text="errors.vehicleRegistration"></span>
                    </div>

                    <div v-if="make" class="mt-8 p-6 bg-slate-100 rounded-md shadow-inner">
                        <div class="flex space-x-1 items-baseline">
                            <p class="font-semibold">Make:</p>
                            <p v-text="make" class="font-medium italic text-sm"></p>
                        </div>
                        <div class="flex space-x-1 items-baseline">
                            <p class="font-semibold">Model:</p>
                            <p v-text="model" class="font-medium italic text-sm"></p>
                        </div>
                        <p class="mt-4">If this is correct, continue to book your appointment</p>
                        <p>If this is incorrect, please manually set your vehicles details</p>

                        <div class="flex mt-8">
                            <button type="button" v-on:click="manuallySetMakeModel" class="p-4 text-white font-semibold bg-orange-700 bg-opacity-75 rounded-md w-full">Manually Enter Vehicle Details</button>
                        </div>
                    </div>

                    <div class="flex mt-8">
                        <button type="button" v-if="!make" v-on:click="getVehicleDetails" class="p-4 text-white font-semibold bg-blue-700 rounded-md w-full">Get Vehicle Details</button>
                        <button type="button" v-else v-on:click="validateStepTwo" class="p-4 text-white font-semibold bg-blue-700 rounded-md w-full">Continue</button>
                    </div>

                    <div v-if="showMakeModelError" class="mt-16 p-6 bg-orange-100 rounded-md shadow-inner">
                        <div class="flex space-x-2 items-center">
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="h-6 w-6 text-orange-400" xmlns="http://www.w3.org/2000/svg">
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path d="M12 5.99 19.53 19H4.47L12 5.99M12 2 1 21h22L12 2z"></path>
                                <path d="M13 16h-2v2h2zM13 10h-2v5h2z"></path>
                            </svg>
                            <p class="text-lg font-semibold">Unknown vehicle registration</p>
                        </div>
                        <p class="mt-4 text-sm">Please check that your number plate is correct. If it is correct, we may have been unable to find it as:</p>
                        <ul class="my-1 list-inside list-disc text-sm">
                            <li>It's a new vehicle</li>
                            <li>There has been a change of number plate</li>
                            <li>It's been registered as SORN</li>
                        </ul>

                        <div class="flex mt-8">
                            <button type="button" v-on:click="manuallySetMakeModel" class="p-4 text-white font-semibold bg-orange-700 bg-opacity-75 rounded-md w-full">Manually Enter Vehicle Details</button>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col space-y-1">
                            <label for="name" class="text-sm font-medium">Vehicle Make <span class="text-red-500">*</span></label>
                            <input type="text" v-model="make" class="p-2 rounded-md">
                            <span v-if="errors.make" class="text-sm text-red-500" v-text="errors.make"></span>
                        </div>
        
                        <div class="flex flex-col space-y-1">
                            <label for="name" class="text-sm font-medium">Vehicle Model <span class="text-red-500">*</span></label>
                            <input type="text" v-model="model" class="p-2 rounded-md">
                            <span v-if="errors.model" class="text-sm text-red-500" v-text="errors.model"></span>
                        </div>
                    </div>

                    <div class="flex mt-8">
                        <button type="button" v-on:click="validateStepTwo" class="p-4 text-white font-semibold bg-sky-500 hover:bg-sky-600 rounded-md w-full transition duration-300">
                            Continue
                        </button>
                    </div>
                </div>
            </div>

            <!-- Calendar Booking -->
            <div v-if="step === 3">
                <div class="flex space-x-4">
                    <div>
                        <input type="text" id="calendar" v-model="calendarDate" autocomplete="off" class="hidden"/>
                    </div>
                    <div class="flex w-full">
                        <div v-if="!calendarDate" class="text-sm">Please select a date to continue</div>
                        <div v-else class="flex flex-col space-y-2 max-h-72 py-2 overflow-y-auto w-full">
                            <button type="button" v-for="(timeslot) in timeslots" 
                                class="px-4 py-2 border rounded-md shadow w-full bg-white disabled:bg-gray-200 disabled:opacity-50" 
                                :class="{ 'border-sky-500 bg-sky-100 text-sky-700': timeslot.timeslot == calendarTime, 'bg-white hover:shadow-md' : !timeslot.booked }"
                                :disabled="timeslot.booked"
                                v-on:click="selectTimeslot(timeslot)"
                            >
                                <p class="text-center" v-text="timeslot.timeslotReadable"></p>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex mt-8">
                    <button type="button" :disabled="!isValidDateTime" v-on:click="confirmBooking" class="p-4 text-white font-semibold bg-sky-500 hover:bg-sky-600 rounded-md w-full transition duration-300 disabled:cursor-not-allowed disabled:bg-sky-300">
                        Submit Booking
                    </button>
                </div>
            </div>

            <!-- Confirmation Page -->
            <div v-if="step === 4">
                <div v-if="showSubmissionError" class="py-8">
                    <div class="flex space-x-2 items-center">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="h-6 w-6 text-orange-400" xmlns="http://www.w3.org/2000/svg">
                            <path fill="none" d="M0 0h24v24H0z"></path>
                            <path d="M12 5.99 19.53 19H4.47L12 5.99M12 2 1 21h22L12 2z"></path>
                            <path d="M13 16h-2v2h2zM13 10h-2v5h2z"></path>
                        </svg>
                        <p class="text-lg font-semibold">Whoops! Seems there's been an issue</p>
                    </div>
                    <p class="mt-4 text-sm">Unfortunately we've been unable to process your request at this time.</p>
                    <p class="text-sm">Please give it a few minutes and try again.</p>
                </div>
                <div v-else>
                    <p class="text-lg font-semibold">That's Done!</p>
                    <p class="mt-4 text-sm">We've received your booking request and you should receive an email shortly to confirm your appointment.</p>
                    <p class="text-sm">If you need to make changes to your appointment, or need to cancel please contact us on XXXXX XXXXXX at least 24 hours in advance.</p>
                    <p class="mt-4 text-sm">We look forward to seeing you soon!</p>
                </div>
            </div>
        </div>
    </div>

</template>


<script>
    import flatpickr from 'flatpickr';

    export default {
        props: [
            'makeModelRoute',
            'bookedDatesRoute',
            'timeslotsRoute',
            'submitBookingRoute'
        ],
        async mounted() {
            const fullyBooked = await this.getFullyBookedDates();
            const self = this;

            flatpickr("#calendar", {
                inline: true,
                minDate: 'today',
                maxDate: new Date().fp_incr(30), // Allow 30 day booking window
                disable: fullyBooked,
                onChange: function(selectedDates, dateStr, instance) {
                    self.getTimeslots(dateStr);
                },
            });
        },
        data() {
            return {
                step: 1,
                name: '',
                email: '',
                phone: '',
                vehicleRegistration: null,
                vehicleMake: null,
                vehicleModel: null,
                timeslot: null,
                dvlaKey: null,
                errors: [],
                make: '',
                model: '',
                showMakeModelError: false,
                showMakeModelManual: false,
                calendarDate: '',
                calendarTime: '',
                fullyBookedDates: [],
                timeslots: [],
                showSubmissionError: false,
                showSuccess: false,
            }
        },
        computed: {
            isValidDateTime() {
                if(this.calendarDate && this.calendarTime) {
                    return true;
                }

                return false;
            },
        },
        methods: {
            validateStepOne(event) {
                event.preventDefault();

                this.errors = [];
                if(!this.name) this.errors.name = 'This field is required';
                if(!this.phone) this.errors.phone = 'This field is required';
                if(!this.email) {
                    this.errors.email = 'This field is required';
                } else {
                    if(!this.isValidEmail(this.email)) this.errors.email = 'Please provide a valid email address';
                }

                if(!Object.keys(this.errors).length) {
                    this.step = 2;
                }
            },
            validateStepTwo(event) {
                event.preventDefault();

                this.errors = [];
                if(!this.make) this.errors.make = 'This field is required';
                if(!this.model) this.errors.model = 'This field is required';

                if(!Object.keys(this.errors).length) {
                    this.step = 3;
                }
            },
            confirmBooking(event) {
                event.preventDefault();
                const success = false;
                try {
                    axios.post(this.submitBookingRoute, {
                        'name': this.name,
                        'email': this.email,
                        'phone': this.phone,
                        'vehicleRegistration': this.vehicleRegistration,
                        'vehicleMake': this.vehicleMake,
                        'vehicleModel': this.vehicleModel,
                        'calendarDate': this.calendarDate,
                        'calenderTime': this.calenderTime
                    }).then(response => {
                        success = response.data;
                    })
                } catch (exception) {
                    console.log(exception);
                }           

                if(!success) {
                    this.showSubmissionError = true; // Submission failed, prompt user to retry later.
                } else {
                    this.showSuccess = true; // Submission success, show success message.
                }

                this.step = 4;
            },
            isValidEmail(email) {
                var validEmailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return validEmailRegex.test(email);
            },
            getVehicleDetails() {
                try {
                    axios.post(this.makeModelRoute, {
                        'registrationNumber': this.vehicleRegistration
                    }).then(response => {
                        if(!response.data.make) {
                            this.showMakeModelError = true;
                            return;
                        }
                        
                        this.make = response.data.make;
                        this.model = response.data.model;
                        this.showMakeModelError = false;
                    })
                } catch (exception) {
                    console.log(exception);
                }
            },
            manuallySetMakeModel() {
                this.showMakeModelManual = true;
            },
            async getFullyBookedDates() {
                let dates = [];
                try {
                    await axios.get(this.bookedDatesRoute).then(response => {
                        console.log(response.data);
                        dates = response.data; // ["2024-02-01", '2024-02-05", ...]
                    })
                } catch (exception) {};

                return dates;
            },
            getTimeslots(date) {
                this.calendarTime = '';
                this.timeslots = [];

                try {
                    axios.post(this.timeslotsRoute, {
                        'date': date
                    }).then(response => {
                        this.timeslots = response.data;
                    })
                } catch (exception) {
                    console.log(exception);
                }
            },
            selectTimeslot(timeslot) {
                if(timeslot.booked) return;

                this.calendarTime = timeslot.timeslot
            }
        }    
    }
</script>
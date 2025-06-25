@component('mail::message')
# New Application for Short Course

**Name:** {{ $data['name'] }}
**Email:** {{ $data['email'] }}
**Phone:** {{ $data['phone'] }}
**Course Applied For:** {{ $data['course'] }}

Thank you for applying!

@endcomponent

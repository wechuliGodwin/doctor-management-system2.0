@component('mail::message')
# New Application for Simulation Course

**Name:** {{ $data['name'] }}
**Email:** {{ $data['email'] }}
**Phone:** {{ $data['phone'] }}
**Course Applied For:** {{ $data['course'] }}



@endcomponent


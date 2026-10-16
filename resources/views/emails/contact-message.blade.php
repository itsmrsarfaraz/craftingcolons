<x-mail::message>
# New transmission received

**Name:** {{ $contact->name }}
**Email:** {{ $contact->email }}
**Quest type:** {{ $contact->quest_type }}

{{ $contact->message }}
</x-mail::message>
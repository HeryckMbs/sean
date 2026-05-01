@props(['faq'])

<li>
    <div class="collapsible-header">
        <i class="material-icons">add_circle_outline</i>
        <span>{{ $faq->question }}</span>
    </div>
    <div class="collapsible-body">
        <p>{{ $faq->answer }}</p>
    </div>
</li>

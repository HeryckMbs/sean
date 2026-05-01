@props(['benefit'])

<article class="benefit-card">
    <i class="material-icons">{{ $benefit->icon ?: 'check_circle' }}</i>
    <div>
        <h3>{{ $benefit->title }}</h3>
        <p>{{ $benefit->description }}</p>
    </div>
</article>

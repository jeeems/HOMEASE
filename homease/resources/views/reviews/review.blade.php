<form action="{{ route('rate.worker') }}" method="POST">
    @csrf
    <input type="hidden" name="worker_id" value="{{ $worker->id }}">

    <label for="rating">Rating (1-5):</label>
    <input type="number" name="rating" min="1" max="5" required>

    <label for="review">Review (optional):</label>
    <textarea name="review" rows="3"></textarea>

    <button type="submit">Submit Rating</button>
</form>

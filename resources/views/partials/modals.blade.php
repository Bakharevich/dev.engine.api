<!-- Default modal for managing from JavaScript -->
<div class="modal fade modal-default" tabindex="-1" role="dialog">
    <form class="modal-default-form">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary btn-send">Отправить</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal for posting quotes -->
<div class="modal fade modal-quote" tabindex="-1" role="dialog">
    <form class="submit-quote">
        <input type="hidden" name="company_id" value="36" />

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Заявка</h4>
                </div>
                <div class="modal-body">
                    <div class="quote-status"></div>
                    <div class="form-group">
                        <p>
                            <label for="exampleInputEmail1">Для:</label> Название компании
                        </p>
                    </div>
                    <div class="form-group">
                        <label for="quote_text">Текст заявки:</label>
                        <textarea id="quote_text" name="quote" class="form-control" rows="6" placeholder="Добрый день! Интересует следующий вопрос... Как быстро вы сможете и сколько стоит... ? Спасибо!"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quote_tel">Телефон:</label>
                        <input type="text" class="form-control" id="quote_tel" name="tel" placeholder="Telephone">
                    </div>
                    <div class="form-group">
                        <label for="quote_email">Email:</label>
                        <input type="text" class="form-control" id="quote_email" name="email" placeholder="Email">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary btn-send-quote">Отправить</button>
                    <div></div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="overlay">
    ...
</div>

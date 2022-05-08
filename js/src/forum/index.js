import app from 'duroom/forum/app';
import { extend } from 'duroom/common/extend';
import Button from 'duroom/common/components/Button';
import EditUserModal from 'duroom/common/components/EditUserModal';
import SignUpModal from 'duroom/forum/components/SignUpModal';
import SettingsPage from 'duroom/forum/components/SettingsPage';
import Model from 'duroom/common/Model';
import User from 'duroom/common/models/User';
import extractText from 'duroom/common/utils/extractText';
import Stream from 'duroom/common/utils/Stream';
import NickNameModal from './components/NicknameModal';

app.initializers.add('duroom/nicknames', () => {
  User.prototype.canEditOwnNickname = Model.attribute('canEditOwnNickname');

  extend(SettingsPage.prototype, 'accountItems', function (items) {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    if (this.user.canEditOwnNickname()) {
      items.add(
        'changeNickname',
        <Button className="Button" onclick={() => app.modal.show(NickNameModal)}>
          {app.translator.trans('duroom-nicknames.forum.settings.change_nickname_button')}
        </Button>
      );
    }
  });

  extend(EditUserModal.prototype, 'oninit', function () {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    this.nickname = Stream(this.attrs.user.displayName());
  });

  extend(EditUserModal.prototype, 'fields', function (items) {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    items.add(
      'nickname',
      <div className="Form-group">
        <label>{app.translator.trans('duroom-nicknames.forum.edit_user.nicknames_heading')}</label>
        <input
          className="FormControl"
          placeholder={extractText(app.translator.trans('duroom-nicknames.forum.edit_user.nicknames_text'))}
          bidi={this.nickname}
        />
      </div>,
      100
    );
  });

  extend(EditUserModal.prototype, 'data', function (data) {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    const user = this.attrs.user;
    if (this.nickname() !== this.attrs.user.displayName()) {
      data.nickname = this.nickname();
    }
  });

  extend(SignUpModal.prototype, 'oninit', function () {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    this.nickname = Stream(this.attrs.username || '');
  });

  extend(SignUpModal.prototype, 'onready', function () {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    if (app.forum.attribute('setNicknameOnRegistration') && app.forum.attribute('randomizeUsernameOnRegistration')) {
      this.$('[name=nickname]').select();
    }
  });

  extend(SignUpModal.prototype, 'fields', function (items) {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    if (app.forum.attribute('setNicknameOnRegistration')) {
      items.add(
        'nickname',
        <div className="Form-group">
          <input
            className="FormControl"
            name="nickname"
            type="text"
            placeholder={extractText(app.translator.trans('duroom-nicknames.forum.sign_up.nickname_placeholder'))}
            bidi={this.nickname}
            disabled={this.loading || this.isProvided('nickname')}
            required={app.forum.attribute('randomizeUsernameOnRegistration')}
          />
        </div>,
        25
      );

      if (app.forum.attribute('randomizeUsernameOnRegistration')) {
        items.remove('username');
      }
    }
  });

  extend(SignUpModal.prototype, 'submitData', function (data) {
    if (app.forum.attribute('displayNameDriver') !== 'nickname') return;

    if (app.forum.attribute('setNicknameOnRegistration')) {
      data.nickname = this.nickname();
      if (app.forum.attribute('randomizeUsernameOnRegistration')) {
        const arr = new Uint32Array(2);
        crypto.getRandomValues(arr);
        data.username = arr.join('');
      }
    }
  });
});

import app from 'duroom/admin/app';
import Alert from 'duroom/common/components/Alert';
import Link from 'duroom/common/components/Link';

app.initializers.add('duroom/nicknames', () => {
  app.extensionData
    .for('duroom-nicknames')
    .registerSetting(function () {
      if (app.data.settings.display_name_driver === 'nickname') return;

      return (
        <div className="Form-group">
          <Alert dismissible={false}>
            {app.translator.trans('duroom-nicknames.admin.wrong_driver', { a: <Link href={app.route('basics')}></Link> })}
          </Alert>
        </div>
      );
    })
    .registerSetting({
      setting: 'duroom-nicknames.set_on_registration',
      type: 'boolean',
      label: app.translator.trans('duroom-nicknames.admin.settings.set_on_registration_label'),
    })
    .registerSetting({
      setting: 'duroom-nicknames.random_username',
      type: 'boolean',
      label: app.translator.trans('duroom-nicknames.admin.settings.random_username_label'),
      help: app.translator.trans('duroom-nicknames.admin.settings.random_username_help'),
    })
    .registerSetting({
      setting: 'duroom-nicknames.unique',
      type: 'boolean',
      label: app.translator.trans('duroom-nicknames.admin.settings.unique_label'),
    })
    .registerSetting({
      setting: 'duroom-nicknames.regex',
      type: 'text',
      label: app.translator.trans('duroom-nicknames.admin.settings.regex_label'),
    })
    .registerSetting({
      setting: 'duroom-nicknames.min',
      type: 'number',
      label: app.translator.trans('duroom-nicknames.admin.settings.min_label'),
    })
    .registerSetting({
      setting: 'duroom-nicknames.max',
      type: 'number',
      label: app.translator.trans('duroom-nicknames.admin.settings.max_label'),
    })
    .registerPermission(
      {
        icon: 'fas fa-user-tag',
        label: app.translator.trans('duroom-nicknames.admin.permissions.edit_own_nickname_label'),
        permission: 'user.editOwnNickname',
      },
      'start'
    );
});

import Modal from 'core/modal';
import Notification from 'core/notification';
import CustomEvents from 'core/custom_interaction_events';
import RemuiblckEvents from 'local_edwiserpagebuilder/remuiblck/events';

const SELECTORS = {
    SAVE_BUTTON: '[data-action="save"]',
    DELETE_BUTTON: '[data-action="delete"]',
    CANCEL_BUTTON: '[data-action="cancel"]',
    SUBJECT: '[name="subject"]',
    SUMMARY: '[name="summary"]',
    DAY: '[name="timedue[day]"]',
    MONTH: '[name="timedue[month]"]',
    YEAR: '[name="timedue[year]"]',
    VISIBLE: '[name="visible"]',
    NOTIFY: '[name="notify"]',
    USERS: '[name="userlist[]"]',
    ELEMENT_ROW: '.fitem',
    ERROR_FEEDBACK: '.form-control-feedback'
};

export default class TaskModal extends Modal {
    static TYPE = 'local_edwiserpagebuilder-task';
    static TEMPLATE = 'local_edwiserpagebuilder/remuiblck/modal_task_popup';

    constructor(root) {
        super(root);
        if (!this.getFooter().find(SELECTORS.SAVE_BUTTON).length) {
            Notification.exception({
                message: M.util.get_string('nosavebutton', 'local_edwiserpagebuilder')
            });
        }
    }

    registerEventListeners() {
        super.registerEventListeners();

        this.getModal().on(CustomEvents.events.activate, SELECTORS.SAVE_BUTTON, () => {
            this.getModal().trigger(RemuiblckEvents.TASK_SAVE);
        });

        this.getModal().on(CustomEvents.events.activate, SELECTORS.DELETE_BUTTON, () => {
            this.getModal().trigger(RemuiblckEvents.TASK_DELETE);
        });

        this.getModal().on(CustomEvents.events.activate, SELECTORS.CANCEL_BUTTON, () => {
            this.getModal().trigger(RemuiblckEvents.TASK_CANCEL);
        });
    }

    valid_settings() {
        const subject = this.getModal().find(SELECTORS.SUBJECT).val();
        this.getModal().find(SELECTORS.SUBJECT)[0].dispatchEvent(new CustomEvent('blur'));
        return subject !== '';
    }

    get_task_settings() {
        const getDate = (modal) => {
            let datetime = modal.find(SELECTORS.YEAR).val();
            const month = modal.find(SELECTORS.MONTH).val();
            const day = modal.find(SELECTORS.DAY).val();
            datetime += '-' + (month < 10 ? '0' + month : month);
            datetime += '-' + (day < 10 ? '0' + day : day);
            datetime += navigator.vendor.indexOf("Apple") !== -1 ? 'T23:59:59' : ' 23:59:59';
            return (navigator.vendor.indexOf("Apple") !== -1 ? Date.parse(datetime) : new Date(datetime).getTime()) / 1000;
        };

        return {
            subject: this.getModal().find(SELECTORS.SUBJECT).val(),
            summary: this.getModal().find(SELECTORS.SUMMARY).val(),
            timedue: getDate(this.getModal()),
            visible: this.getModal().find(SELECTORS.VISIBLE).is(':checked'),
            notify: this.getModal().find(SELECTORS.NOTIFY).is(':checked'),
            users: this.getModal().find(SELECTORS.USERS).val()
        };
    }

    saving(action = true) {
        const button = this.getModal().find(SELECTORS.SAVE_BUTTON);
        if (action) {
            button.text(M.util.get_string('saving', 'core_repository')).attr('disabled', 'disabled');
            button.attr('disabled', true);
            return;
        }
        button.attr('disabled', false);
        button.text(M.util.get_string('save', 'core_repository')).removeAttr('disabled');
    }
}

TaskModal.registerModalType();

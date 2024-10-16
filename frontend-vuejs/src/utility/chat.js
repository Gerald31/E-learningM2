import { isTutor } from "@/utility/roles";

export const type_text = 'text';
export const type_file = 'file';

export const getFullName = (contact) => {
    return contact.firstname + ' ' + contact.lastname;
}

export const getRolesContact = (contact) => {
    if (isTutor(contact.roles)) {
        return 'Tuteur';
    } else {
        return 'Etudiant';
    }
}

// test if the message contains attachments
export const hasAttachments = (message) => {
    return message?.attachment;
};

// trim message content.
export const shorten = (message, maxLength = 23) => {
    return shortenText(message.body, maxLength);
};

// trim string.
export const shortenText = (text, maxLength = 23) => {
    if (text) {
        let trimmedString = text;

        if (text.length > maxLength) {
            // trim the string to the maximum length.
            trimmedString = trimmedString.slice(0, maxLength);
            // add three dots to indicate that there is more to the message.
            trimmedString += '...';
        }

        return trimmedString;
    }

    return '';
};

import React from 'react';
import ReactDOM from 'react-dom';

class ContactForm extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            fullnameValue: '',
            emailValue: '',
            email: '',
            passwordValue: '',
            fullnameError: '',
            emailError: '',
            passwordError: '',
            successMessage: '',
            messageTetxarea: '',
        };
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(e) {
        if(e.target.name === 'fullname') {
            this.setState({
                fullnameValue: e.target.value
            });
        }

        if(e.target.name === 'email') {
            this.setState({
                emailValue: e.target.value,
            });
        }

        if(e.target.name === 'message') {
            this.setState({
                messageValue: e.target.value,
            });
        }
    }

    handleSubmit(e) {
        e.preventDefault();

        let data = {
            name: this.state.fullnameValue,
            email: this.state.emailValue,
            message: this.state.messageValue
        };

        var test = document.getElementById('contactForm');

        console.log(test);

        $.ajax({
            url: 'https://beeterr.com/api/contact/1',
            type: 'POST',
            data: JSON.stringify(data),
            dataType: 'json',
            success: function(response) {
                this.setState({
                    fullnameError: response.fullnameError ? response.fullnameError : null,
                    emailError: response.emailError ? response.emailError : null,
                    passwordError: response.passwordError ? response.passwordError : null,
                    successMessage: response.success_message ? response.success_message : null,
                });
            }.bind(this),
            error: function(xhr) {
                console.log(`An error occured: ${xhr.status} ${xhr.statusText}`);
            }
        });
    }

    render() {
        return (
            <form className="py-4" onSubmit={this.handleSubmit}>
                <div className="form-group">
                    <label htmlFor="inputName" className="small text-uppercase">Name <span className="text-primary">*</span></label>
                    <input type="text" className="form-control" id="fullname" placeholder="Enter your name" name='fullname' value={this.state.fullnameValue} onChange={this.handleChange} />
                </div>
                <div className="form-group">
                    <label htmlFor="inputEmail" className="small text-uppercase">Email <span className="text-primary">*</span></label>
                    <input type="email" className="form-control" id="email" placeholder="Email" name='email' value={this.state.emailValue} onChange={this.handleChange} />
                </div>
                <div className="form-group">
                    <label htmlFor="messageTetxarea" className="small text-uppercase">Message <span className="text-primary">*</span></label>
                    <textarea className="form-control rounded" id="message" rows="9" placeholder="Enter your message" name="message" value={this.state.messageValue} onChange={this.handleChange}></textarea>
                </div>
                <button type="submit" className="btn btn-pill btn-primary">Submit</button>
            </form>
        );
    }
}

ReactDOM.render(<ContactForm />, document.getElementById('contactForm'));

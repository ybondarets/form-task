import React from 'react';
import {useState, useEffect, useMemo} from 'react';
import DatePicker from "react-datepicker";
import Autocomplete from 'react-autocomplete';
import CompanyQuotes from '../components/CompanyQuotes';
import { findCompanyData, findCompaniesData } from '../api/api';
import "react-datepicker/dist/react-datepicker.css";

export default function FindCompanyForm() {
    const [email, setEmail] = useState('');
    const [emailError, setEmailError] = useState('');
    const [startDate, setStartDate] = useState(null);
    const [startDateError, setStartDateError] = useState(null);
    const [endDate, setEndDate] = useState(null);
    const [endDateError, setEndDateError] = useState(null);
    const [companyCode, setCompanyCode] = useState('');
    const [companyCodeError, setCompanyCodeError] = useState('');
    const [codes, setCodes] = useState([])
    const [generalErrors, setGeneralErrors] = useState([]);
    const [validationFailed, setValidationFailed] = useState(false);

    const [companyData, setCompanyData] = useState([]);

    useEffect(() => {
        let mounted = true;
        findCompaniesData()
            .then(response => {
                if(mounted) {
                    setCodes(response.data);
                }
            })
        return () => mounted = false;
    }, []);

    const formatDate = (date) => {
        return date ? date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() : '';
    }

    const isValidEmail = (email) => {
        if (!email) return false;
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        return re.test(String(email).toLowerCase());
    }

    const isValidCompanyCode = (code) => {
        return codes.find((companyData) => companyData["Symbol"] === code);
    };

    const isValidDate = (date) => {
        return date instanceof Date;
    }

    const validate = () => {
        setValidationFailed(false);
        setEmailError();
        setCompanyCodeError();
        setEndDateError();
        setStartDateError();

        if (!isValidEmail(email)) {
            setEmailError('Email is not valid');
            setValidationFailed(true);
        }

        if (!isValidCompanyCode(companyCode)) {
            setCompanyCodeError('Company code is not valid');
            setValidationFailed(true);
        }

        if (!isValidDate(startDate)) {
            setStartDateError('Start date error is not valid');
            setValidationFailed(true);
        }

        if (!isValidDate(endDate)) {
            setEndDateError('Start date error is not valid');
            setValidationFailed(true);
        }

        const now = new Date();
        if (endDate > now) {
            setEndDateError('End date can\'t be greater than now.')
            setValidationFailed(true);
        }

        if (startDate > now) {
            setStartDateError('Start date can\'t be greater than now.')
            setValidationFailed(true);
        }

        if (endDate < startDate) {
            const errors = generalErrors;
            errors.push('End date can\'t be less than start date');
            setGeneralErrors(errors);
            setValidationFailed(true);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        validate();

        if (validationFailed === false) {
            const data = {
                email,
                startDate: formatDate(startDate),
                endDate: formatDate(endDate),
                companyCode,
            };

            // send request
            setCompanyData(null);
            setGeneralErrors([]);
            findCompanyData(data)
                .then((response) => {
                    if (response) {
                        if (response.data.hasOwnProperty('errors')) {
                            setGeneralErrors(response.data.errors);
                        } else {
                            setCompanyData(response.data);
                        }
                    }
                }).catch((error) => {
                    console.error(error);
                });
        }
    };

    const today = new Date();
    const defineMaxStartDate = (end) => {
        if (!end) return today;

        return end < today ? end : today;
    };

    const maxStartDate = useMemo(() => defineMaxStartDate(endDate), [endDate]);

    return (
        <div>
        <form onSubmit={handleSubmit}>
            <div className="row">
                <label htmlFor="email">Email</label>
                <input
                    type="text"
                    name="email"
                    value={email}
                    onChange={e => setEmail(e.target.value)}
                    id="email"
                    placeholder="email"
                />
                <small>{emailError}</small>
            </div>

            <div className="row">
                <label htmlFor="startDate">Start date</label>
                <DatePicker
                    selected={startDate}
                    onChange={setStartDate}
                    name="startDate"
                    placeholder="Start Date"
                    selectsStart
                    startDate={startDate}
                    endDate={maxStartDate}
                    maxDate={maxStartDate}
                    dateFormat="yyyy-MM-dd"
                />
                <small>{startDateError}</small>
            </div>

            <div className="row">
                <label htmlFor="endDate">End date</label>
                <DatePicker
                    selected={endDate}
                    onChange={setEndDate}
                    name="endDate"
                    placeholder="End Date"
                    selectsEnd
                    startDate={startDate}
                    endDate={today}
                    minDate={startDate}
                    maxDate={today}
                    dateFormat="yyyy-MM-dd"
                />
                <small>{endDateError}</small>
            </div>

            <div className="row">
                <label htmlFor="companyCode">Company code</label>
                <Autocomplete
                    shouldItemRender={(item, value) => item.Symbol.toLowerCase().indexOf(value.toLowerCase()) > -1}
                    getItemValue={(item) => item.Symbol}
                    items={codes}
                    renderItem={(item, isHighlighted) =>
                        <div style={{ background: isHighlighted ? 'lightgray' : 'white' }} key={item.Symbol}>
                            {item.Symbol} ({item['Company Name']})
                        </div>
                    }
                    value={companyCode}
                    onChange={(e) => setCompanyCode(e.target.value)}
                    onSelect={(val) => setCompanyCode(val)}
                />
                <small>{companyCodeError}</small>
            </div>

            {generalErrors.map(error => (<p>{error}</p>))}

            <button type="submit">Get data</button>
        </form>

        <CompanyQuotes companyQuotes={companyData}/>
        </div>
    );
}
